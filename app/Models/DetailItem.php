<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Task;
use App\Models\Interfaces\Item;
use App\Models\Traits\Deletable;

class DetailItem extends Model implements Item
{
    use Deletable;

    protected $fillable = [
        'task_id', 'list_element_id', 'type', 'content'
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public static function newItem(Task $task, string $content)
    {
        $uniqueID = uniqid();

        $item = self::create([
            'task_id' => $task->id,
            'list_element_id' => $uniqueID,
            'type' => 'deadline',
            'content' => $content
        ]);

        $item->task()->associate($task);
        $item->save();

        $task->addItem($item);

        $list = $task->subcategory->category->taskList;
        $list->addListElement('detailItem', $content, $uniqueID);

        return $item;
    }

    public function updateItem(Item $item, string $content)
    {
        $item->content = $content;
        $item->save();

        return $item;
    }
}
