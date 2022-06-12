<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource {
  /**
   * Transform the resource into an array.
   *
   * @param \Illuminate\Http\Request $request
   * @return array
   */
  public function toArray($request): array {

    /**
     * @var \App\Models\Task
     */
    $task = $this->resource;

    return [
      'id' => $task->id,
      'user_id' => $task->user_id,
      'entity_id' => $task->entity_id,
      'entity_type' => $task->entity_type,
      'value' => $task->value,
      'is_moderated' => $task->is_moderated,
      'is_accepted' => $task->is_accepted,
      'comment' => $task->comment,
      'user' => $this->whenLoaded('user', UserResource::make($task->user))
    ];
  }
}
