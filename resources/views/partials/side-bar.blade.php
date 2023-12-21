<div class="card overflow-hidden">
    <div class="card-body pt-3">
        <ul class="nav nav-link-secondary flex-column fw-bold gap-2">
            <li class="nav-item">
                <a class="{{ (Route::is('DashBoard')) ? 'text-white bg-primary rounded' : ''}}nav-link" href="{{ route('DashBoard')}}">
                    <span>Home</span></a>
            </li>
            <li class="nav-item">
                <a class="{{ (Route::is('feed')) ? 'text-white bg-primary rounded' : ''}} nav-link" href="{{route('feed')}}">
                    <span>Feed</span></a>
            </li>
        </ul>
    </div>
</div>
