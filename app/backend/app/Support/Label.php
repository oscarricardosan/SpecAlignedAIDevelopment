<?php

namespace App\Support;

class Label
{
    /**
     * Labels that cannot be derived from the raw value.
     */
    private static array $map = [
        // Sectors
        'finance'        => 'Finance & Banking',
        'healthcare'     => 'Healthcare',
        'education'      => 'Education',
        'ecommerce'      => 'E-commerce & Retail',
        'logistics'      => 'Logistics & Transport',
        'government'     => 'Government & Public Sector',
        'entertainment'  => 'Entertainment & Media',
        'manufacturing'  => 'Manufacturing & Industry',
        'other'          => 'Other',
        // Audiences
        'b2b'            => 'B2B',
        'b2c'            => 'B2C',
        // Compliance
        'none'           => 'None',
        'gdpr'           => 'GDPR',
        'hipaa'          => 'HIPAA',
        'pci_dss'        => 'PCI-DSS',
        'soc2'           => 'SOC 2',
        'iso_27001'      => 'ISO 27001',
    ];

    /**
     * Convert a machine value to a human-readable label.
     * Falls back to ucwords with underscore-to-space conversion.
     */
    public static function humanize(string $value): string
    {
        return self::$map[$value]
            ?? ucwords(str_replace('_', ' ', $value));
    }

    /**
     * Return all option value → label pairs for a given field.
     * Used by selects and checkboxes so options live in a single place.
     */
    public static function options(string $field): array
    {
        return match ($field) {
            'criticality' => [
                'mission_critical'   => 'Mission Critical — downtime causes severe impact',
                'business_important' => 'Business Important — affects daily operations',
                'administrative'     => 'Administrative — supports internal processes',
                'experimental'       => 'Experimental — proof of concept or internal tool',
            ],
            'business_sector' => [
                'finance'        => 'Finance & Banking',
                'healthcare'     => 'Healthcare',
                'education'      => 'Education',
                'ecommerce'      => 'E-commerce & Retail',
                'logistics'      => 'Logistics & Transport',
                'government'     => 'Government & Public Sector',
                'entertainment'  => 'Entertainment & Media',
                'manufacturing'  => 'Manufacturing & Industry',
                'other'          => 'Other / Not listed',
            ],
            'target_audience' => [
                'internal' => 'Internal — Employees or staff within your organization',
                'b2b'      => 'B2B — Other businesses or enterprise clients',
                'b2c'      => 'B2C — End consumers, general public',
                'public'   => 'Public — Anyone, no authentication required',
            ],
            'compliance' => [
                'none'       => 'None — No regulatory requirements apply',
                'gdpr'       => 'GDPR — European data protection',
                'hipaa'      => 'HIPAA — US healthcare law',
                'pci_dss'    => 'PCI-DSS — Payment card security',
                'soc2'       => 'SOC 2 — Security, availability & confidentiality',
                'iso_27001'  => 'ISO 27001 — Global information security standard',
            ],
            default => [],
        };
    }
}
