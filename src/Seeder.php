<?php
namespace Minhbang\Ebook;

use Minhbang\Enum\Enum;
use Minhbang\Category\Category;
use Minhbang\Kit\Support\VnString;
use DB;
use Carbon\Carbon;
use File;
use Spatie\PdfToImage\Pdf;

/**
 * Class Seeder
 *
 * @package Minhbang\Ebook
 */
class Seeder
{
    /**
     * - Tự động lấy trang đầu của PDF làm cover
     * - Thứ tự data:
     * 0 title, 1 filename(ko đuôi file), 2 summary, 3 pyear, 4 pages, 5 category_slug, 6 security_slug,
     * 7 language, 8 writer, 9 publisher, 10 pplace, 11 user_id, 12 status, 13 hit, 14 featured
     *
     * @param string $dir
     * @param array $data
     *
     * @throws \Spatie\PdfToImage\Exceptions\InvalidFormat
     */
    public function seed($dir, $data)
    {
        DB::table('ebooks')->truncate();
        $data_dir = storage_path('data/' . config('ebook.data_dir'));
        $tmp_dir = storage_path('tmp');
        $img_dir = public_path(setting('system.public_files') . '/' . config('ebook.featured_image.dir'));
        $ebooks = [];
        foreach ($data as $ebook) {
            $category = Category::findBy('slug', $ebook[5]);
            $security = Enum::where('slug', $ebook[6])->where('type', 'ebook.security')->first();
            if ($security && $category && is_file("$dir/{$ebook[1]}.pdf")) {
                $pdf = new Pdf("$dir/{$ebook[1]}.pdf");
                $pdf->saveImage("$tmp_dir/{$ebook[1]}.png");
                $image_file = save_new_image("{$ebook[1]}.png", "$tmp_dir/{$ebook[1]}.png", $img_dir, [
                    'main' => [
                        'width'  => config('ebook.featured_image.width'),
                        'height' => config('ebook.featured_image.height'),
                        'method' => 'resize',
                    ],
                    'sm'   => [
                        'width'  => config('ebook.featured_image.width_sm'),
                        'height' => config('ebook.featured_image.height_sm'),
                        'method' => 'resize',
                    ],
                ]);
                @unlink("$tmp_dir/{$ebook[1]}.png");

                File::copy("$dir/{$ebook[1]}.pdf", "$data_dir/{$ebook[1]}.pdf");

                $ebooks[] = [
                    'title'          => $ebook[0],
                    'slug'           => VnString::to_slug($ebook[0]),
                    'filename'       => $ebook[1] . '.pdf',
                    'filemime'       => 'application/pdf',
                    'filesize'       => File::size("$dir/{$ebook[1]}.pdf"),
                    'summary'        => $ebook[2],
                    'featured_image' => $image_file,
                    'pyear'          => $ebook[3],
                    'pages'          => $ebook[4],
                    'category_id'    => $category->id,
                    'security_id'    => $security->id,
                    'language_id'    => Enum::firstOrCreate(['title' => $ebook[7], 'type' => 'ebook.language'], ['slug' => VnString::to_slug($ebook[7])])->id,
                    'writer_id'      => Enum::firstOrCreate(['title' => $ebook[8], 'type' => 'ebook.writer'], ['slug' => VnString::to_slug($ebook[8])])->id,
                    'publisher_id'   => Enum::firstOrCreate(['title' => $ebook[9], 'type' => 'ebook.publisher'], ['slug' => VnString::to_slug($ebook[9])])->id,
                    'pplace_id'      => Enum::firstOrCreate(['title' => $ebook[10], 'type' => 'ebook.pplace'], ['slug' => VnString::to_slug($ebook[10])])->id,
                    'user_id'        => $ebook[11],
                    'status'         => $ebook[12],
                    'hit'            => $ebook[13],
                    'featured'       => $ebook[14],
                    'created_at'     => Carbon::now(),
                    'updated_at'     => Carbon::now(),
                ];
            }
        }
        if ($ebooks) {
            DB::table('ebooks')->insert($ebooks);
        }
    }
}