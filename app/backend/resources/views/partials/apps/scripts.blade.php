<script>
function appForm(projectPath = '', appData = null) {
    return {
        step: 1,
        submitting: false,
        projectPath: projectPath,
        platform: '{{ old('platform', 'web') }}',
        language: '',
        languageCustom: '',
        languageVersion: '',
        languageVersionCustom: '',
        tech: '',
        techCustom: '',
        frameworkVersion: '',
        frameworkVersionCustom: '',
        paradigm: '',
        architecture: '',
        databases: [],
        databaseAccess: '',
        testings: '',
        storages: [],
        appName: '',
        appDescription: '',
        appPath: '',
        packageManager: '',
        executor: 'local',
        codeRepository: '',
        repoUrl: '',
        contextTab: 'architecture',
        contextStack: '',
        contextArchitecture: '',
        contextGuidelines: '',

        // Folder browser
        openBrowser: false,
        appCurrentPath: '', appCurrentFullPath: '',
        appItems: [], appBreadcrumbs: [], appLoading: false, appError: '',
        appCreating: false, appNewFolderName: '',

        init() {
            // Pre-populate from application data when editing
            if (appData) {
                this.platform = appData.platform || 'web';
                this.language = appData.language_is_custom ? 'other' : (appData.language || '');
                this.languageCustom = appData.language_is_custom ? (appData.language || '') : '';
                this.languageVersion = appData.language_version_is_custom ? 'custom' : (appData.language_version || '');
                this.languageVersionCustom = appData.language_version_is_custom ? (appData.language_version || '') : '';
                this.tech = appData.technology_is_custom ? 'other' : (appData.technology || '');
                this.techCustom = appData.technology_is_custom ? (appData.technology || '') : '';
                this.frameworkVersion = appData.framework_version_is_custom ? 'custom' : (appData.framework_version || '');
                this.frameworkVersionCustom = appData.framework_version_is_custom ? (appData.framework_version || '') : '';
                this.paradigm = appData.paradigm || '';
                this.architecture = appData.architecture || '';
                this.databases = appData.databases_codes || [];
                this.databaseAccess = appData.database_access || '';
                this.testings = appData.testing_frameworks_codes || '';
                this.storages = appData.storage_codes || [];
                this.appName = appData.name || '';
                this.appDescription = appData.description || '';
                this.appPath = appData.path || '';
                this.packageManager = appData.package_manager || '';
                this.executor = appData.executor || 'local';
                this.codeRepository = appData.code_repository || '';
                this.repoUrl = appData.code_repository_url || '';
                this.contextStack = appData.context_stack || '';
                this.contextArchitecture = appData.context_architecture || '';
                this.contextGuidelines = appData.context_guidelines || '';
            }

            this.$watch('platform', () => { this.language = ''; this.languageCustom = ''; this.languageVersion = ''; this.languageVersionCustom = ''; this.tech = ''; this.techCustom = ''; this.frameworkVersion = ''; this.databases = []; this.paradigm = ''; });
            this.$watch('language', () => { this.tech = this.defaultNoFramework(); this.techCustom = ''; this.frameworkVersion = ''; this.frameworkVersionCustom = ''; this.languageVersion = ''; this.languageVersionCustom = ''; this.languageCustom = ''; this.packageManager = this.defaultPackageManager(); });
            this.$watch('tech', () => { this.frameworkVersion = ''; this.frameworkVersionCustom = ''; if (this.tech !== 'other') this.techCustom = ''; this.packageManager = this.defaultPackageManager(); });
            this.$watch('architecture', () => { this.paradigm = ''; });
            if (this.projectPath) {
                this.appBrowse(this.projectPath);
            }
        },

        goToStep(n) { if (n < this.step || this.isStepComplete(n - 1)) { this.step = n; window.scrollTo({ top: 0, behavior: 'smooth' }); } },
        isStepComplete(s) {
            switch (s) {
                case 1: return !!this.platform;
                case 2:
                    if (!this.language) return false;
                    if (this.language === 'other' && !this.languageCustom.trim()) return false;
                    if (this.languageVersion === 'custom' && !this.languageVersionCustom.trim()) return false;
                    return true;
                case 3:
                    if (!this.tech) return false;
                    if (this.tech === 'other' && !this.techCustom.trim()) return false;
                    if (this.frameworkVersion === 'custom' && !this.frameworkVersionCustom.trim()) return false;
                    return true;
                case 4: return !this.databases.length || !!this.databaseAccess;
                case 5: return !!this.architecture;
                case 7: return !!this.appName.trim() && !!this.appPath.trim() && this.appBreadcrumbs.length > 2;
                default: return true;
            }
        },
        canProceed() { return this.isStepComplete(this.step); },

        // ── Folder browser ──
        async appBrowse(path) {
            this.appLoading = true; this.appError = ''; this.appItems = [];
            try {
                const res = await fetch(`{{ route('api.filesystem.browse') }}?path=${encodeURIComponent(path)}`);
                if (!res.ok) { const b = await res.json().catch(() => ({})); throw new Error(b.error || 'Could not load folder contents.'); }
                const data = await res.json();
                this.appCurrentPath = data.path; this.appCurrentFullPath = data.full_path;
                this.appBreadcrumbs = data.breadcrumbs; this.appItems = data.items;
            } catch (e) { this.appError = e.message; }
            finally { this.appLoading = false; }
        },
        appGoUp() { if (this.appBreadcrumbs.length > 1) this.appNavigate(this.appBreadcrumbs[this.appBreadcrumbs.length - 2].path); },
        appNavigate(path) { this.appCreating = false; this.appNewFolderName = ''; this.appBrowse(path); },
        appSelectCurrent() { this.appPath = this.appCurrentFullPath; },
        async appCreateFolder() {
            const name = this.appNewFolderName.trim(); if (!name) return;
            let currentPath = this.appCurrentPath;
            if (!currentPath && this.projectPath) {
                currentPath = this.projectPath;
            }
            if (!currentPath) {
                this.appError = 'Cannot create folder outside the project.';
                return;
            }
            this.appError = '';
            try {
                const res = await fetch('{{ route('api.filesystem.directory.create') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '', 'Accept': 'application/json' },
                    body: JSON.stringify({ path: currentPath, name }),
                });
                if (!res.ok) { const b = await res.json().catch(() => ({})); throw new Error(b.error || 'Could not create folder.'); }
                this.appNewFolderName = ''; this.appCreating = false; await this.appBrowse(currentPath);
            } catch (e) { this.appError = e.message; }
        },

        toggleStorage(v,c) { if(c){if(!this.storages.includes(v))this.storages.push(v);}else{this.storages=this.storages.filter(s=>s!==v);} },
        toggleDatabase(v,c) { if(c){if(!this.databases.includes(v))this.databases.push(v);}else{this.databases=this.databases.filter(d=>d!==v);} },
        toggleTesting(v,c) { this.testings = c ? v : ''; },

        // ── Package manager ──
        defaultPackageManager() {
            const g = this.techGroup(this.tech); const m = { php:'composer', js:'npm', py:'pip', rust:'cargo', go:'go', dotnet:'nuget', dart:'pub', mobile:'npm' };
            return m[g] || '';
        },
        defaultNoFramework() {
            const opts = this.frameworkOptions();
            if (!opts.length) return '';
            const nf = opts.find(o => o.nf);
            return nf ? nf.value : opts[0].value;
        },
        packageManagerOptions() {
            const g = this.techGroup(this.tech);
            const base = [
                { value:'composer', label:'Composer', icon:'devicon:composer', group:['php'] },
                { value:'npm', label:'npm', icon:'devicon:npm', group:['js','mobile'] },
                { value:'yarn', label:'Yarn', icon:'devicon:yarn', group:['js','mobile'] },
                { value:'pnpm', label:'pnpm', icon:'devicon:pnpm', group:['js','mobile'] },
                { value:'bun', label:'Bun', icon:'devicon:bun', group:['js','mobile'] },
                { value:'pip', label:'pip', icon:'devicon:python', group:['py'] },
                { value:'poetry', label:'Poetry', icon:'devicon:poetry', group:['py'] },
                { value:'uv', label:'uv', icon:'devicon:python', group:['py'] },
                { value:'cargo', label:'Cargo', icon:'devicon:rust', group:['rust'] },
                { value:'go', label:'go mod', icon:'devicon:go', group:['go'] },
                { value:'nuget', label:'NuGet', icon:'devicon:nuget', group:['dotnet'] },
                { value:'dotnet', label:'dotnet CLI', icon:'devicon:dotnetcore', group:['dotnet'] },
                { value:'pub', label:'pub', icon:'devicon:dart', group:['dart'] },
            ];
            const filtered = base.filter(o => o.group.includes(g));
            return filtered;
        },

        // ── Language ──
        languageIconFor(lang) {
            const f = [{ v:'php',i:'devicon:php'},{v:'javascript',i:'devicon:javascript'},{v:'typescript',i:'devicon:typescript'},{v:'python',i:'devicon:python'},{v:'csharp',i:'devicon:csharp'},{v:'rust',i:'devicon:rust'},{v:'go',i:'devicon:go'},{v:'dart',i:'devicon:dart'},{v:'deno',i:'devicon:denojs'}].find(o=>o.v===lang);
            return f ? f.i : 'heroicons:code-bracket';
        },
        languageLabel() { if(this.language==='other'&&this.languageCustom) return this.languageCustom; const o = this.languageOptions().find(o=>o.value===this.language); return o?o.label:''; },
        languageOptions() {
            const all = [
                { value:'php', label:'PHP', desc:'Server-side scripting', icon:'devicon:php', platforms:['web','api','cli'] },
                { value:'javascript', label:'JavaScript', desc:'The language of the browser', icon:'devicon:javascript', platforms:['web'] },
                { value:'typescript', label:'TypeScript', desc:'JavaScript with types', icon:'devicon:typescript', platforms:['web','api','cli'] },
                { value:'python', label:'Python', desc:'General purpose, data & AI', icon:'devicon:python', platforms:['web','api','cli'] },
                { value:'csharp', label:'C#', desc:'.NET ecosystem', icon:'devicon:csharp', platforms:['web','api','desktop'] },
                { value:'rust', label:'Rust', desc:'Systems programming', icon:'devicon:rust', platforms:['web','api','cli','desktop'] },
                { value:'go', label:'Go', desc:'Cloud & networking', icon:'devicon:go', platforms:['api','cli'] },
                { value:'dart', label:'Dart', desc:'Flutter & mobile', icon:'devicon:dart', platforms:['mobile','desktop'] },
                { value:'deno', label:'Deno', desc:'Modern JS/TS runtime', icon:'devicon:denojs', platforms:['web','api','cli'] },
            ];
            let o = all.filter(o=>o.platforms.includes(this.platform));
            o.push({ value:'other', label:'Other', desc:'Specify in context', icon:'heroicons:ellipsis-horizontal-circle', platforms:[] });
            return o;
        },
        languageVersionOptions() {
            const m = {
                'php':['8.1','8.2','8.3','8.4','custom'], 'javascript':['ES2020','ES2021','ES2022','ES2023','ES2024','custom'],
                'typescript':['4.9','5.0','5.1','5.2','5.3','5.4','5.5','5.6','custom'], 'python':['3.10','3.11','3.12','3.13','custom'],
                'csharp':['.NET 6','.NET 7','.NET 8','.NET 9','custom'], 'rust':['1.75','1.76','1.77','1.78','1.79','1.80','1.81','1.82','custom'],
                'go':['1.20','1.21','1.22','1.23','custom'], 'dart':['3.0','3.1','3.2','3.3','3.4','3.5','custom'], 'deno':['1.x','2.x','custom'],
            };
            return (m[this.language]||[]).map(v=>({value:v,label:v}));
        },

        // ── Framework ──
        frameworkOptions() {
            const li = this.languageIconFor(this.language);
            const all = [
                { value:'vanilla_php', label:'No framework', desc:'Plain PHP', icon:'devicon:php', lang:'php', nf:true },
                { value:'laravel', label:'Laravel', desc:'Full-stack MVC', icon:'devicon:laravel', lang:'php' },
                { value:'symfony', label:'Symfony', desc:'Enterprise components', icon:'devicon:symfony', lang:'php' },
                { value:'cakephp', label:'CakePHP', desc:'Rapid development', icon:'devicon:cakephp', lang:'php' },
                { value:'codeigniter', label:'CodeIgniter', desc:'Lightweight MVC', icon:'devicon-plain:codeigniter', lang:'php' },
                { value:'yii', label:'Yii', desc:'High-performance', icon:'devicon:yii', lang:'php' },
                { value:'other', label:'Other', desc:'Specify below', icon:li, lang:'php', ot:true },
                { value:'vanilla_js', label:'No framework', desc:'Plain JavaScript', icon:'devicon:javascript', lang:'javascript', nf:true },
                { value:'react', label:'React', desc:'UI library', icon:'devicon:react', lang:'javascript' },
                { value:'vue', label:'Vue.js', desc:'Progressive framework', icon:'devicon:vuejs', lang:'javascript' },
                { value:'angular', label:'Angular', desc:'Full platform', icon:'devicon:angular', lang:'javascript' },
                { value:'svelte', label:'Svelte', desc:'Compiled reactivity', icon:'devicon:svelte', lang:'javascript' },
                { value:'other', label:'Other', desc:'Specify below', icon:li, lang:'javascript', ot:true },
                { value:'vanilla_ts', label:'No framework', desc:'Plain TypeScript', icon:'devicon:typescript', lang:'typescript', nf:true },
                { value:'react_ts', label:'React + TS', desc:'Typed UI', icon:'devicon:react', lang:'typescript' },
                { value:'vue_ts', label:'Vue.js + TS', desc:'Typed Vue', icon:'devicon:vuejs', lang:'typescript' },
                { value:'angular', label:'Angular', desc:'TypeScript-first', icon:'devicon:angular', lang:'typescript' },
                { value:'node_express', label:'Express', desc:'Minimal HTTP', icon:'devicon:express', lang:'typescript' },
                { value:'node_nest', label:'NestJS', desc:'Enterprise Node', icon:'devicon:nestjs', lang:'typescript' },
                { value:'other', label:'Other', desc:'Specify below', icon:li, lang:'typescript', ot:true },
                { value:'vanilla_py', label:'No framework', desc:'Plain Python', icon:'devicon:python', lang:'python', nf:true },
                { value:'django', label:'Django', desc:'Batteries included', icon:'simple-icons:django', lang:'python' },
                { value:'fastapi', label:'FastAPI', desc:'Async REST', icon:'devicon:fastapi', lang:'python' },
                { value:'flask', label:'Flask', desc:'Microframework', icon:'devicon:flask', lang:'python' },
                { value:'other', label:'Other', desc:'Specify below', icon:li, lang:'python', ot:true },
                { value:'dotnet', label:'.NET', desc:'ASP.NET / Blazor', icon:'devicon:dotnetcore', lang:'csharp' },
                { value:'other', label:'Other', desc:'Specify below', icon:li, lang:'csharp', ot:true },
                { value:'vanilla_rust', label:'No framework', desc:'Plain Rust', icon:'devicon:rust', lang:'rust', nf:true },
                { value:'rust_actix', label:'Actix Web', desc:'High-performance', icon:'devicon:rust', lang:'rust' },
                { value:'rust_axum', label:'Axum', desc:'Tokio-native', icon:'devicon:rust', lang:'rust' },
                { value:'tauri', label:'Tauri', desc:'Desktop with web UI', icon:'devicon:rust', lang:'rust' },
                { value:'other', label:'Other', desc:'Specify below', icon:li, lang:'rust', ot:true },
                { value:'go', label:'Go', desc:'net/http or Gin', icon:'devicon:go', lang:'go' },
                { value:'other', label:'Other', desc:'Specify below', icon:li, lang:'go', ot:true },
                { value:'flutter', label:'Flutter', desc:'Cross-platform', icon:'devicon:flutter', lang:'dart' },
                { value:'other', label:'Other', desc:'Specify below', icon:li, lang:'dart', ot:true },
                { value:'deno', label:'Deno', desc:'Native runtime', icon:'devicon:denojs', lang:'deno' },
                { value:'deno_fresh', label:'Fresh', desc:'Full-stack Deno', icon:'devicon:denojs', lang:'deno' },
                { value:'other', label:'Other', desc:'Specify below', icon:li, lang:'deno', ot:true },
                { value:'react_native', label:'React Native', desc:'Cross-platform', icon:'devicon:react', lang:'javascript' },
                { value:'react_native_ts', label:'React Native + TS', desc:'Typed mobile', icon:'devicon:react', lang:'typescript' },
            ];
            let o = all.filter(o=>o.lang===this.language);
            const s = new Set(); o = o.filter(o=>{if(s.has(o.value))return false;s.add(o.value);return true;});
            o.sort((a,b)=>{if(a.nf&&!b.nf)return -1;if(!a.nf&&b.nf)return 1;if(a.ot&&!b.ot)return 1;if(!a.ot&&b.ot)return -1;return 0;});
            return o;
        },
        frameworkVersionOptions() {
            const m = {
                'laravel':['10.x','11.x','12.x','13.x','custom'], 'symfony':['5.4 LTS','6.x LTS','7.x','custom'],
                'react':['17.x','18.x','19.x','custom'], 'vue':['2.x','3.x','custom'], 'angular':['16.x','17.x','18.x','19.x','custom'],
                'node_express':['4.x','5.x','custom'], 'node_nest':['9.x','10.x','11.x','custom'],
                'django':['3.x LTS','4.x LTS','5.x','custom'], 'fastapi':['0.95+','0.100+','0.110+','0.115+','custom'], 'flask':['2.x','3.x','custom'],
                'dotnet':['6 LTS','7','8 LTS','9','custom'], 'rust_actix':['4.x','custom'], 'rust_axum':['0.6','0.7','0.8','custom'],
                'go':['1.21','1.22','1.23','custom'], 'flutter':['3.16','3.19','3.22','3.24','custom'],
                'react_native':['0.73','0.74','0.75','0.76','custom'], 'tauri':['1.x','2.x','custom'],
                'deno':['1.x','2.x','custom'], 'deno_fresh':['1.x','2.x','custom'],
            };
            return (m[this.tech]||[]).map(v=>({value:v,label:v}));
        },
        techIcon() { const f=this.frameworkOptions().find(o=>o.value===this.tech); if(f)return f.icon; const l=this.languageOptions().find(o=>o.value===this.language); return l?l.icon:null; },
        techLabel() { if(this.tech==='other'&&this.techCustom)return this.techCustom; const o=this.frameworkOptions().find(o=>o.value===this.tech); return o?o.label:''; },
        techGroup(t) {
            const php=['laravel','symfony','vanilla_php','cakephp','codeigniter','yii'];
            const js=['react','react_ts','vue','vue_ts','angular','vanilla_js','vanilla_ts','node_express','node_nest','react_native','react_native_ts','svelte'];
            const py=['django','fastapi','flask','vanilla_py']; const mb=['flutter','react_native','react_native_ts'];
            const rs=['rust_actix','rust_axum','vanilla_rust','tauri']; const dn=['dotnet'];
            if(php.includes(t))return'php'; if(js.includes(t))return'js'; if(py.includes(t))return'py';
            if(mb.includes(t))return'mobile'; if(rs.includes(t))return'rust'; if(dn.includes(t))return'dotnet';
            if(t==='go')return'go'; if(t==='deno'||t==='deno_fresh')return'js'; return'other';
        },
        paradigmOptions() {
            const all = [
                { value:'oop', label:'OOP — Object-Oriented', architectures:['mvc','repository','clean','hexagonal','microservices','monolith'] },
                { value:'functional', label:'Functional — pure functions, immutability', architectures:['clean','hexagonal','microservices','serverless','event_driven'] },
                { value:'procedural', label:'Procedural — step by step', architectures:['mvc','monolith','serverless'] },
                { value:'reactive', label:'Reactive — streams, observables', architectures:['microservices','serverless','event_driven'] },
                { value:'hybrid', label:'Hybrid — mix of paradigms', architectures:['mvc','repository','clean','hexagonal','microservices','monolith','serverless','event_driven'] },
            ];
            if (!this.architecture) return all;
            return all.filter(p => p.architectures.includes(this.architecture));
        },
        paradigmShortLabel() { const f=this.paradigmOptions().find(o=>o.value===this.paradigm); return f?f.label.replace(/ —.*/,''):''; },
        architectureShortLabel() {
            const opts = {!! json_encode(\App\Support\Label::options('architecture')) !!};
            const label = opts[this.architecture] || this.architecture;
            return label.split(' — ')[0];
        },
        architectureOptions() {
            const icons = {
                mvc: 'heroicons:square-3-stack-3d',
                repository: 'heroicons:archive-box',
                clean: 'heroicons:cube-transparent',
                hexagonal: 'heroicons:view-columns',
                microservices: 'heroicons:server-stack',
                monolith: 'heroicons:building-library',
                serverless: 'heroicons:cloud-arrow-up',
                event_driven: 'heroicons:bolt',
            };
            const descs = {
                mvc: 'Separates data, UI, and logic into three interconnected components.',
                repository: 'Abstracts data access behind interfaces for testability and swap.',
                clean: 'Entities, use cases, adapters — business logic at the center.',
                hexagonal: 'Domain isolated from I/O — ports define boundaries, adapters implement.',
                microservices: 'Independent deployable services, each with a single responsibility.',
                monolith: 'Modular single deployable — simple ops, shared codebase.',
                serverless: 'Function-as-a-service — no server management, scales to zero.',
                event_driven: 'Events and message brokers decouple producers from consumers.',
            };
            const all = [
                { value:'mvc', label:'MVC', paradigms:['oop','procedural','hybrid'] },
                { value:'repository', label:'Repository Pattern', paradigms:['oop','hybrid'] },
                { value:'clean', label:'Clean Architecture', paradigms:['oop','functional','hybrid'] },
                { value:'hexagonal', label:'Hexagonal / Ports & Adapters', paradigms:['oop','functional','hybrid'] },
                { value:'microservices', label:'Microservices', paradigms:['oop','reactive','hybrid'] },
                { value:'monolith', label:'Monolith', paradigms:['oop','procedural','hybrid'] },
                { value:'serverless', label:'Serverless / Lambda', paradigms:['functional','procedural','reactive','hybrid'] },
                { value:'event_driven', label:'Event-Driven', paradigms:['functional','reactive','hybrid'] },
            ];
            const mapped = all.map(a => ({ ...a, icon: icons[a.value] || 'heroicons:question-mark-circle', desc: descs[a.value] || '' }));
            return mapped;
        },
        databaseLabel(v) { const o={!! json_encode(\App\Support\Label::options('database')) !!}; return o[v]||v; },
        platformLabel() { const m={web:'Browser App',api:'API Backend',mobile:'Mobile App',desktop:'Desktop App',cli:'CLI Tool'}; return m[this.platform]||''; },
        selectedPrinciples() {
            const c=document.querySelectorAll('input[name="design_principles[]"]:checked');
            return Array.from(c).map(cb=>{const l=cb.closest('label');return l?l.querySelector('.font-semibold')?.textContent?.trim()||cb.value:cb.value;});
        },
        testingOptions() {
            const g=this.techGroup(this.tech); const li=this.languageIconFor(this.language)||'heroicons:beaker';
            const all = [
                { value:'phpunit', label:'PHPUnit', icon:li, group:['php'] },
                { value:'pest', label:'Pest', icon:li, group:['php'] },
                { value:'jest', label:'Jest', icon:'logos:jest', group:['js','mobile'] },
                { value:'vitest', label:'Vitest', icon:'logos:vitest', group:['js'] },
                { value:'pytest', label:'PyTest', icon:'logos:pytest', group:['py'] },
                { value:'xunit', label:'xUnit', icon:li, group:['dotnet'] },
                { value:'nunit', label:'NUnit', icon:li, group:['dotnet'] },
                { value:'cargo_test', label:'cargo test', icon:li, group:['rust'] },
                { value:'go_test', label:'go test', icon:li, group:['go'] },
                { value:'flutter_test', label:'Flutter Test', icon:li, group:['mobile'] },
            ];
            return all.filter(o=>o.group.includes(g));
        },
        codeStyleOptions() {
            const g=this.techGroup(this.tech);
            return [
                { value:'psr12', label:'PSR-12 (PHP)', group:['php'] }, { value:'pep8', label:'PEP 8 (Python)', group:['py'] },
                { value:'airbnb', label:'Airbnb (JS / React)', group:['js','mobile'] }, { value:'standard', label:'Standard JS', group:['js','mobile'] },
                { value:'google_js', label:'Google Style (JS)', group:['js','mobile'] }, { value:'google_py', label:'Google Style (Python)', group:['py'] },
                { value:'rustfmt', label:'rustfmt (Rust)', group:['rust'] }, { value:'gofmt', label:'gofmt / go vet', group:['go'] },
                { value:'microsoft', label:'Microsoft C# conventions', group:['dotnet'] },
            ].filter(o=>o.group.includes(g));
        },
    };
}
</script>
