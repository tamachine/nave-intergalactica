<?php

namespace App\Http\Livewire\Blog\Post;

use App\Models\BlogAuthor;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogTag;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;

    /*
    ***************************************************************
    ** PROPERTIES
    ***************************************************************
    */

    /**
     * @var App\Models\BlogPost
     */
    public $post;

    /**
     * @var string
     */
    public $title;

    /**
     * @var bool
     */
    public $published;

    /**
     * @var string
     */
    public $category;

    /**
     * @var array
     */
    public $categories;

    /**
     * @var string
     */
    public $author;

    /**
     * @var array
     */
    public $authors;

    /**
     * @var string
     */
    public $tags;

    /**
     * @var string
     */
    public $summary;

    /**
     * @var string
     */
    public $content;

    /**
     * @var object
     */
    public $featured;

    /**
     * @var string
     */
    public $featured_url = '';

    /*
    ***************************************************************
    ** METHODS
    ***************************************************************
    */

    public function mount(BlogPost $post)
    {
        $this->post = $post;
        $this->title = $post->title;
        $this->published = $post->published;
        $this->category = $post->blog_category_id;
        $this->author = $post->blog_author_id;
        $this->tags = $post->tags_string;
        $this->summary = $post->summary;
        $this->content = $post->content;
        $this->featured_url = $post->featured_image_url;

        $this->categories = BlogCategory::pluck('name', 'id');
        $this->authors = BlogAuthor::pluck('name', 'id');
    }

    public function savePost()
    {
        $this->dispatchBrowserEvent('validationError');

        $rules = [
            'title'     => ['required'],
            'category'  => ['required'],
            'author'    => ['required'],
            'featured'  => ['nullable', 'mimes:jpeg,jpg,png,gif'],
        ];

        $this->validate($rules);

        // Check if we need to update the 'published_at' date
        if (!$this->post->published && $this->published) {
            $published_date = now();
        } else {
            $published_date = $this->post->published_at;
        }

        // 1. Update the post
        $this->post->update([
            'title'             => $this->title,
            'slug'              => slugify($this->title),
            'published'         => $this->published ? 1 : 0,
            'published_at'      => $published_date,
            'summary'           => $this->summary,
            'content'           => $this->content,
            'blog_category_id'  => $this->category,
            'blog_author_id'    => $this->author,
        ]);

        // 2. Update the featured image
        if ($this->featured) {
            $extension = $this->featured->getClientOriginalExtension();
            $filename = $this->post->hashid . "." . $extension;
            $this->featured->storeAs("public/posts" , $filename);

            $this->post->update([
                'featured_image' => $filename,
            ]);
        }

        // 3. Update the tags
        $this->post->tags()->detach();
        if (!emptyOrNull($this->tags)) {
            $tags = explode(',', $this->tags);

            foreach($tags as $tag) {
                $currentTag = BlogTag::firstOrCreate([
                    'name'  => trim($tag),
                    'slug'  => slugify(trim($tag)),
                ]);
                $this->post->tags()->attach($currentTag->id);
            }
        }

        session()->flash('status', 'success');
        session()->flash('message', 'Post "' . $this->title . '" updated');

        return redirect()->route('blog.post.index');
    }

    public function deletePost()
    {
        $this->post->delete();

        session()->flash('status', 'success');
        session()->flash('message', __('The post has been deleted'));

        return redirect()->route('blog.post.index');
    }

    public function render()
    {
        return view('livewire.blog.post.edit');
    }
}
