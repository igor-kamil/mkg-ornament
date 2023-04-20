<?php

namespace App\Http\Resources;

use App\Models\Authority;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'work_id' => $this->workID,
            'title' => $this->identified_by_Appellation_label_value,
            'author' => $this->was_present_at_Activity_had_participant_actor_label_value,
            'dating' => $this->dating,
            'description' => $this->has_type_comment,
            'image_src' => $this->resource_link_resource_value_internal,
            'web_url' => $this->isShownAt_uri,
            'collection' => $this->member_of_collection_label_value,
            'object' => $this->referred_to_by_LinguisticObject_content_value,
            'year_from' => $this->was_present_at_Activity_has_time_span_begin_of_the_begin,
            'year_to' => ($this->was_present_at_Activity_has_time_span_end_of_the_end) ? (int)Str::before($this->was_present_at_Activity_has_time_span_end_of_the_end, '-'): null,
        ];
    }

    private function getImageRoute($width = 600)
    {
        return config('services.webumenia.url') . '/dielo/nahlad/' . $this->id . '/' . $width;
    }

    private function getDescription()
    {
        if ($this['item']->description) {
            return nl2br($this['item']->description);
        }

        return $this->description;
    }

    private function getAuthor()
    {
        if ($this['item']->author_name) {
            return $this['item']->author_name;
        }

        return collect($this['webumenia_item']->authors)
            ->map(fn (string $author) => formatName($author))
            ->join(', ');
    }
}
