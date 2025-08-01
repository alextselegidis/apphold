<div class="bg-primary">
    <div class="container">
        <div class="row">
            <div class="col">
                <nav class="navbar navbar-expand-lg">
                    <a class="navbar-brand d-flex align-items-center p-0 m-0" href="{{route('dashboard')}}">
                        <img src="images/logo.png" alt="Logo" class="me-2" style="height: 32px">
                        <strong class="fs-4 text-white">
                            APPHOLD
                        </strong>
                    </a>

                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#top-nav"
                            aria-controls="top-nav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse ms-md-4" id="top-nav">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0 w-100">
                            <li class="nav-item dropdown ms-md-auto me-0">
                                <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown"
                                   aria-expanded="false">
                                    <i class="bi bi-person me-2"></i>
                                    {{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                     <li >
                                         <a class="dropdown-item" href="{{route('settings')}}">
                                             {{__('settings')}}
                                         </a>
                                     </li>
                                    <li>
                                        <a class="dropdown-item" href="{{route('account')}}">
                                            {{__('account')}}
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{route('about')}}">
                                            {{__('about')}}
                                        </a>
                                    </li>
                                    <li>
                                        <form action="{{route('logout.perform')}}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item"
                                                    href="{{route('logout.perform')}}">
                                                {{__('logout')}}
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</div>


