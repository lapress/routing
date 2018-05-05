<?php

namespace LaPress\Routing\Http\Resources;

use LaPress\Models\AbstractPost;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
class PostResourceResolver
{
    const BASE_NAMESPACE_PATTERN = 'App\\Http\\Resources\\%sResource';
    /**
     * @var AbstractPost
     */
    private $post;

    /**
     * @param AbstractPost $post
     */
    public function __construct(AbstractPost $post)
    {
        $this->post = $post;
    }

    /**
     * @return JsonResource;
     */
    public function resolve(): JsonResource
    {
        $class = $this->getClass();

        return new $class($this->post);
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        if (class_exists($this->getClassName($this->post->getPostType()))) {
            return $this->getClassName($this->post->getPostType());
        }

        if (class_exists($this->getClassName('post'))) {
            return $this->getClassName('post');
        }

        return PostResource::class;
    }

    /**
     * @param string $key
     * @return string
     */
    public function getClassName(string $key): string
    {
        return sprintf(static::BASE_NAMESPACE_PATTERN, ucfirst($key));
    }
}