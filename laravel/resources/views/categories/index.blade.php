<h1>Список категорій</h1>
<a href="{{ route('categories.create') }}">Додати нову категорію</a>
<hr>
<ul>
    @foreach($categories as $category)
        <li><strong>{{ $category->name }}</strong>: {{ $category->description }}</li>
    @endforeach
</ul>