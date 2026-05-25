@extends("install.layout")

@section("step_description")
    Create the administrator account. This user will have full access to the platform
    and will be able to manage projects, agents, and other users.
@endsection

@section("content")
<h2 class="text-xl font-bold text-navy mb-2">Create admin user</h2>
<p class="text-sm text-warm mb-6">
    This will be the first user of the platform — with full administrator privileges.
    You can add more users later from the dashboard.
</p>

<form action="{{ route('install.user.save') }}" method="POST" class="space-y-4"
      x-data="{ submitting: false }" @submit="submitting = true">
    @csrf
    <div>
        <label class="block text-sm font-medium text-navy mb-1">Full name</label>
        <input name="name" value="{{ old('name') }}"
               class="input input-bordered w-full bg-white text-sm text-navy placeholder:text-cool/60 focus:outline-none focus:border-teal focus:ring-2 focus:ring-teal/20 transition"
               placeholder="e.g. Jane Smith" required>
        <p class="text-xs text-cool mt-1">How you'll be identified in the platform.</p>
    </div>
    <div>
        <label class="block text-sm font-medium text-navy mb-1">Email address</label>
        <input name="email" type="email" value="{{ old('email') }}"
               class="input input-bordered w-full bg-white text-sm text-navy placeholder:text-cool/60 focus:outline-none focus:border-teal focus:ring-2 focus:ring-teal/20 transition"
               placeholder="you@example.com" required>
        <p class="text-xs text-cool mt-1">Used to sign in. Won't be shared.</p>
    </div>
    <div>
        <label class="block text-sm font-medium text-navy mb-1">Password</label>
        <input name="password" type="password"
               class="input input-bordered w-full bg-white text-sm text-navy placeholder:text-cool/60 focus:outline-none focus:border-teal focus:ring-2 focus:ring-teal/20 transition"
               placeholder="At least 8 characters" required minlength="8">
        <p class="text-xs text-cool mt-1">Minimum 8 characters. Use a strong password.</p>
    </div>
    <div>
        <label class="block text-sm font-medium text-navy mb-1">Confirm password</label>
        <input name="password_confirmation" type="password"
               class="input input-bordered w-full bg-white text-sm text-navy placeholder:text-cool/60 focus:outline-none focus:border-teal focus:ring-2 focus:ring-teal/20 transition"
               placeholder="Type the same password" required minlength="8">
    </div>
    <button type="submit"
            :disabled="submitting"
            class="btn btn-primary w-full disabled:opacity-60 disabled:cursor-not-allowed">
        <span x-show="!submitting">Create user and finish installation</span>
        <span x-show="submitting" class="flex items-center gap-2">
            <iconify-icon icon="svg-spinners:180-ring" class="w-4 h-4"></iconify-icon>
            Creating&hellip;
        </span>
    </button>
</form>
@endsection
