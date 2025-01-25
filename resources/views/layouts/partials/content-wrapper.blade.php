<!-- resources/views/layouts/partials/content-wrapper.blade.php -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>@yield('page_title')</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        @yield('breadcrumb')
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        @yield('content')
    </section>
</div>
