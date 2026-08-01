<?php

if (! function_exists('rupiah')) {
    /**
     * Format an integer amount as Indonesian Rupiah, e.g. 25000 -> "Rp25.000"
     */
    function rupiah(int|float $amount): string
    {
        return 'Rp' . number_format((float) $amount, 0, ',', '.');
    }
}
