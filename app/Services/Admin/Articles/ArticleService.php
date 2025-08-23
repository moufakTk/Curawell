<?php

namespace App\Services\Admin\Articles;

use App\Models\Article;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class ArticleService
{
    protected string $disk = 'public';
    protected string $dir  = 'articles';
    protected string $imageType = 'article'; // غيّرها إذا حابب تميّز أنواع صور

    public function create(array $data): Article
    {
        return DB::transaction(function () use ($data) {
            /** @var Article $article */
            $article = Article::create([
                'title_en'             => $data['title_en'],
                'title_ar'             => $data['title_ar'],
                'brief_description_en' => $data['brief_description_en'],
                'brief_description_ar' => $data['brief_description_ar'],
                'path_link'            => $data['path_link'] ?? null,
                'is_active'            => $data['is_active'] ?? true,
            ]);

            // خزّن صورة واحدة عبر علاقة المورف
            if (!empty($data['image']) && $data['image'] instanceof UploadedFile) {
                $path = $this->storeImage($data['image']);
                $article->image()->create([
                    'path_image' => $path,
                    'type'       => $this->imageType,
                ]);
            }

            // رجّعها مع الصورة (وتحمل image.url من الـ accessor تبع Image)
            return $article->fresh('image');
        });
    }

    public function update(Article $article, array $data): Article
    {
        return DB::transaction(function () use ($article, $data) {

            // تبديل الصورة (يحذف القديمة ويضيف الجديدة)
            if (!empty($data['image']) && $data['image'] instanceof UploadedFile) {
                $this->replaceMorphImage($article, $data['image']);
            }

            // حقول قابلة للتحديث
            $updatable = array_intersect_key($data, array_flip([
                'title_en','title_ar',
                'brief_description_en','brief_description_ar',
                'path_link','is_active'
            ]));

            if (!empty($updatable)) {
                $article->update($updatable);
            }

            return $article->fresh('image');
        });
    }

    public function delete(Article $article): void
    {
        DB::transaction(function () use ($article) {
            // احذف الصورة من الستوريج ثم السجل
            $img = $article->image;
            if ($img && $img->path_image) {
                if (Storage::disk($this->disk)->exists($img->path_image)) {
                    Storage::disk($this->disk)->delete($img->path_image);
                }
                $img->delete();
            }

            $article->delete();
        });
    }

    protected function storeImage(UploadedFile $file): string
    {
        $ext  = $file->getClientOriginalExtension();
        $name = Str::uuid()->toString().'.'.strtolower($ext);
        // بيرجع مسار مثل: articles/uuid.webp
        return $file->storeAs($this->dir, $name, $this->disk);
    }

    protected function replaceMorphImage(Article $article, UploadedFile $file): void
    {
        // احذف القديمة إن وجدت
        $old = $article->image;
        if ($old && $old->path_image) {
            if (Storage::disk($this->disk)->exists($old->path_image)) {
                Storage::disk($this->disk)->delete($old->path_image);
            }
            $old->delete();
        }

        // خزّن الجديدة
        $path = $this->storeImage($file);
        $article->image()->create([
            'path_image' => $path,
            'type'       => $this->imageType,
        ]);
    }
}
