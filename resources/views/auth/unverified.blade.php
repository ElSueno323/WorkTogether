<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('Your account is registered but needs to be manually accepted by an administrator. In the mean time,
        please enjoy this cat gif.') }}
    </div>

    <img src="img/cat.gif" alt="i love silly cats">

    <div class="mt-4 flex items-center justify-between">
        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <x-primary-button type="submit">
                {{ __('Log Out') }}
            </x-primary-button>
        </form>
    </div>
</x-guest-layout>