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
        // Design principles (acronyms)
        'solid'          => 'SOLID',
        'dry'            => 'DRY',
        'kiss'           => 'KISS',
        'yagni'          => 'YAGNI',
        'tdd'            => 'TDD',
        // CI/CD
        'github_actions' => 'GitHub Actions',
        'gitlab_ci'      => 'GitLab CI/CD',
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
            'technology' => [
                // PHP
                'laravel'      => 'Laravel',
                'symfony'      => 'Symfony',
                'vanilla_php'  => 'Vanilla PHP',
                // JavaScript / TypeScript
                'react'        => 'React',
                'vue'          => 'Vue.js',
                'angular'      => 'Angular',
                'vanilla_js'   => 'Vanilla JS',
                // Node.js
                'node_express' => 'Express',
                'node_nest'    => 'NestJS',
                // Python
                'django'       => 'Django',
                'fastapi'      => 'FastAPI',
                'flask'        => 'Flask',
                // .NET
                'dotnet'       => '.NET (C#)',
                // Rust
                'rust_actix'   => 'Actix Web',
                'rust_axum'    => 'Axum',
                'vanilla_rust' => 'Vanilla Rust',
                // Go
                'go'           => 'Go',
                // Mobile / Desktop
                'flutter'      => 'Flutter',
                'react_native' => 'React Native',
                'tauri'        => 'Tauri',
                // Deno
                'deno'         => 'Deno',
                'deno_fresh'   => 'Deno Fresh',
                // Other
                'other'        => 'Other',
            ],
            'platform' => [
                'web'     => 'Web — Browser-based application',
                'mobile'  => 'Mobile — iOS / Android app',
                'desktop' => 'Desktop — Native desktop application',
                'api'     => 'API Backend — Headless API consumed by other apps',
                'cli'     => 'CLI Tool — Command-line interface',
            ],
            'architecture' => [
                'mvc'           => 'MVC — Model-View-Controller',
                'repository'    => 'Repository Pattern — abstract data access behind interfaces',
                'clean'         => 'Clean Architecture — entities, use cases, adapters',
                'hexagonal'     => 'Hexagonal / Ports & Adapters — domain isolated from I/O',
                'microservices' => 'Microservices — independent deployable services',
                'monolith'      => 'Monolith — modular, single deployable',
                'serverless'    => 'Serverless / Lambda — function-as-a-service',
                'event_driven'  => 'Event-Driven — events and message brokers',
            ],
            'database' => [
                'postgresql' => 'PostgreSQL',
                'mysql'      => 'MySQL / MariaDB',
                'sqlite'     => 'SQLite',
                'sqlserver'  => 'SQL Server',
                'oracle'     => 'Oracle DB',
                'mongodb'    => 'MongoDB',
                'none'       => 'None — no database',
            ],
            'database_access' => [
                'orm'           => 'ORM — Eloquent, Doctrine, SQLAlchemy, Prisma…',
                'query_builder' => 'Query Builder — fluent API, no full ORM overhead',
                'raw_sql'       => 'Raw SQL — hand-written queries',
                'repository'    => 'Repository Pattern — abstracted behind interfaces',
            ],
            'storage' => [
                'local'      => 'Local filesystem',
                's3'         => 'Amazon S3',
                's3_compat'  => 'S3-compatible (MinIO, DigitalOcean, etc.)',
                'ftp'        => 'FTP',
                'sftp'       => 'SFTP',
                'azure_blob' => 'Azure Blob Storage',
                'gcs'        => 'Google Cloud Storage',
                'none'       => 'No file storage needed',
            ],
            'code_style' => [
                'psr12'    => 'PSR-12 (PHP)',
                'pep8'     => 'PEP 8 (Python)',
                'airbnb'   => 'Airbnb Style (JavaScript / React)',
                'standard' => 'Standard JS',
                'google'   => 'Google Style',
                'custom'   => 'Custom — define later',
            ],
            'design_principle' => [
                'solid'                    => 'SOLID — Single responsibility, open/closed, Liskov, segregation, dependency inversion',
                'dry'                      => 'DRY — Don\'t Repeat Yourself',
                'kiss'                     => 'KISS — Keep It Simple, Stupid',
                'yagni'                    => 'YAGNI — You Aren\'t Gonna Need It',
                'separation_of_concerns'   => 'Separation of Concerns — one thing per component',
                'composition_over_inheritance' => 'Composition over Inheritance — favor has-a over is-a',
                'fail_fast'                => 'Fail Fast — validate early, report immediately',
                'convention_over_config'   => 'Convention over Configuration — sensible defaults',
                'tdd'                      => 'TDD — Test-Driven Development (red, green, refactor)',
            ],
            'testing_framework' => [
                'phpunit' => 'PHPUnit (PHP)',
                'pest'    => 'Pest (PHP)',
                'jest'    => 'Jest (JavaScript / TypeScript)',
                'vitest'  => 'Vitest (JavaScript / TypeScript)',
                'pytest'  => 'PyTest (Python)',
                'flutter_test' => 'Flutter Test (Dart)',
                'none'    => 'None — no tests for now',
            ],
            'ci_cd' => [
                'github_actions' => 'GitHub Actions',
                'gitlab_ci'      => 'GitLab CI/CD',
                'jenkins'        => 'Jenkins',
                'circleci'       => 'CircleCI',
                'none'           => 'None — no CI/CD yet',
            ],
            'code_repository' => [
                'github'    => 'GitHub',
                'gitlab'    => 'GitLab',
                'gitea'     => 'Gitea',
                'bitbucket' => 'Bitbucket',
                'other'     => 'Other',
                'none'      => 'None — no remote repository',
            ],
            'programming_paradigm' => [
                'oop'       => 'Object-Oriented Programming',
                'functional'=> 'Functional Programming — pure functions, immutability',
                'procedural'=> 'Procedural — step-by-step scripts',
                'reactive'  => 'Reactive — streams, observables, event loops',
                'hybrid'    => 'Hybrid — mix of paradigms',
            ],
            default => [],
        };
    }
}
