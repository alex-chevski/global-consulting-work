<td>
    <p class="text-muted">
        @foreach ($product->data as $key => $value)
            {{ "{$key}: {$value}" }} <br>
        @endforeach
    </p>
</td>
