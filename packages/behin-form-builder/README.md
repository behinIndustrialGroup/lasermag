add map api key to .env file
```
MAP_API_KEY=
```

## Searchable input field

A searchable input type can be used when you need to fetch options from an API as the user types (for example for a `customer_id` field).

```php
echo app(\MyFormBuilder\Services\FormBuilder::class)->searchableInput('customer_id', [
    'endpoint' => route('customers.search'), // endpoint should return [{"id":1,"label":"Alice"}, ...]
    'minChars' => 3, // minimum characters before the endpoint is called
    'limit' => 5, // optional maximum number of results to show (null shows all)
    'value' => 1000, // optional initial id
    'initial_label' => 'مشتری نمونه', // optional label for the initial id; if omitted the endpoint is called with ?id=1000
    'placeholder' => 'جستجوی مشتری...',
]);
```

