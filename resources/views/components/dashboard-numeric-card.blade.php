@props(['icon', 'title', 'count'])
@vite(['resources/css/components/dashboard-numeric-card.css'])
<div class="card">
    <div class="icon-container">
        <i class="fa-regular {{ $icon }}"></i>
    </div>
    <div class="card-details">
        <h3>{{ $title }}</h3>
        <p>{{ $count }}</p>
    </div>
</div>
