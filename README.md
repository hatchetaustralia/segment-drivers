# SegmentTrap


## Example Usage

```php
Segment::track('itemAddedToCart', [
    'sku' => $sku,
]);

Segment::driver()->track('itemAddedToCart', [
    'sku' => $sku,
]);

Segment::driver('queue')->track('itemAddedToCart', [
    'sku' => $sku,
]);
```

## Available Drivers:

- `stack`: Dispatches the Segment events to various drivers
    - Can configure various other drivers to write to.
- `sync`: Dispatches the Segment events immediately
- `after`: Dispatches the Segment events after the response
- `queue`: Dispatches the Segment events via a queue worker
    - Can configure the queue connection and queue name
- `log`: Dispatches the Segment events to a log channel
    - Can configure the logger channel
- `eloquent`: Dispatches the Segment events to a model/the DB.
    - Can configure the model to use
- `null`: Dispatches the Segment events to the void
