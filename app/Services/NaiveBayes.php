<?php

namespace App\Services;

class NaiveBayes
{
    private $model = [];
    private $classCounts = [];
    private $totalCount = 0;
    private $classes = ['Unggul', 'Baik', 'Cukup'];
    private $features = [
        'ipa', 'ips', 'bhs_indonesia', 'matematika',
        'doa_iftitah', 'tahiyat_awal', 'qunut',
        'membaca_al_quran', 'fatihah_4', 'doa', 'menulis'
    ];

    // Tentukan label kelas berdasarkan total nilai
    public function getLabel($data): string
    {
        $total = array_sum(array_intersect_key($data, array_flip($this->features)));
        if ($total >= 700) return 'Unggul';
        if ($total >= 550) return 'Baik';
        return 'Cukup';
    }

    // Training: hitung mean dan std dev tiap fitur per kelas
    public function train(array $dataset): void
    {
        $grouped = ['Unggul' => [], 'Baik' => [], 'Cukup' => []];

        foreach ($dataset as $data) {
            $label = $this->getLabel($data);
            $grouped[$label][] = $data;
        }

        $this->totalCount = count($dataset);

        foreach ($this->classes as $class) {
            $rows = $grouped[$class];
            $this->classCounts[$class] = count($rows);

            foreach ($this->features as $feature) {
                $values = array_column($rows, $feature);
                $this->model[$class][$feature] = [
                    'mean' => $this->mean($values),
                    'std'  => $this->std($values),
                ];
            }
        }
    }

    // Prediksi kelas untuk satu data
    public function predict(array $data): array
    {
        $probabilities = [];

        foreach ($this->classes as $class) {
            // Prior probability P(class)
            $prior = $this->classCounts[$class] / $this->totalCount;
            $logProb = log($prior + 1e-10);

            // Likelihood P(feature|class)
            foreach ($this->features as $feature) {
                $mean = $this->model[$class][$feature]['mean'];
                $std  = $this->model[$class][$feature]['std'];
                $x    = $data[$feature] ?? 0;
                $logProb += log($this->gaussian($x, $mean, $std) + 1e-10);
            }

            $probabilities[$class] = $logProb;
        }

        // Konversi log probability ke probability normal
        $maxLog = max($probabilities);
        $expProbs = [];
        $sumExp = 0;

        foreach ($probabilities as $class => $logP) {
            $expProbs[$class] = exp($logP - $maxLog);
            $sumExp += $expProbs[$class];
        }

        $normalizedProbs = [];
        foreach ($expProbs as $class => $val) {
            $normalizedProbs[$class] = round(($val / $sumExp) * 100, 2);
        }

        // Prediksi = kelas dengan probabilitas tertinggi
        $predicted = array_search(max($normalizedProbs), $normalizedProbs);

        return [
            'predicted'     => $predicted,
            'probabilities' => $normalizedProbs,
        ];
    }

    // Gaussian probability density function
    private function gaussian(float $x, float $mean, float $std): float
    {
        if ($std == 0) $std = 1e-10;
        $exp = exp(-pow($x - $mean, 2) / (2 * pow($std, 2)));
        return (1 / (sqrt(2 * M_PI) * $std)) * $exp;
    }

    private function mean(array $values): float
    {
        if (count($values) === 0) return 0;
        return array_sum($values) / count($values);
    }

    private function std(array $values): float
    {
        $n = count($values);
        if ($n <= 1) return 1e-10;
        $mean = $this->mean($values);
        $variance = array_sum(array_map(fn($v) => pow($v - $mean, 2), $values)) / $n;
        return sqrt($variance);
    }

    public function getModel(): array { return $this->model; }
    public function getClassCounts(): array { return $this->classCounts; }
    public function getFeatures(): array { return $this->features; }
    public function getClasses(): array { return $this->classes; }
}