<x-root-layout>
    @vite(['resources/css/layout.css', 'resources/js/welcome.js', 'resources/js/echo.js'])
    <!-- Navbar -->
    @include('layouts.navigation')

    <section class="main-section">
        @include('components.header', ['title' => $title])
        <!-- Page Content -->
        <main>
            <div class="d-none banner" id="notification-banner">
                <div class="banner-content">
                    <p id="notification-text">You have received a notification!</p>
                    <i class="fa-solid fa-xmark"></i>
                </div>
            </div>
            @if ($errors->any() || $errors->updatePassword->any())
                <div class="banner" id="error-banner">
                    <div class="banner-content">
                        <div class="banner-texts">
                            <h3>Error!</h3>
                            <p>{{ $errors->first() ?: $errors->updatePassword->first() }}</p>
                        </div>
                        <i class="fa-solid fa-xmark"></i>
                    </div>
                </div>
            @endif
            @if (session('status'))
                <div class="banner" id="success-banner">
                    <div class="banner-content">
                        <div class="banner-texts">
                            <h3>Success!</h3>
                            <p>{{ session('status') }}</p>
                        </div>
                        <i class="fa-solid fa-xmark"></i>
                    </div>
                </div>
            @endif
            {{ $slot }}
        </main>
    </section>
    <script>
        document.querySelectorAll(".banner .fa-xmark").forEach(item => {
            item.addEventListener("click", (e) => {
                e.target.parentNode.parentNode.classList.add("d-none");
            })
        });

        // const dialog = document.querySelectorAll("dialog");
        // outsideDialogClicked(dialog);
        //
        // function outsideDialogClicked(dialogs) {
        //     if (dialog.length < 1)
        //         return;
        //     dialogs.forEach(dialog => {
        //         dialog.addEventListener("click", (e) => {
        //             const dialogDimensions = dialog.getBoundingClientRect();
        //             if (e.clientX < dialogDimensions.left ||
        //                 e.clientX > dialogDimensions.right ||
        //                 e.clientY < dialogDimensions.top ||
        //                 e.clientY > dialogDimensions.bottom) {
        //                 dialog.close();
        //             }
        //         })
        //     })
        // }
    </script>
</x-root-layout>
