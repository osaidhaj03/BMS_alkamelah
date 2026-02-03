<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Models\Book;
use App\Models\Author;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the sitemap.xml file for the website';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting sitemap generation...');
        $startTime = microtime(true);

        $sitemap = Sitemap::create();

        // 1. Static Pages
        $this->info('Adding static pages...');
        $sitemap->add(Url::create(route('home'))
            ->setPriority(1.0)
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY));
            
        $sitemap->add(Url::create(route('about'))
            ->setPriority(0.8));

        $sitemap->add(Url::create(route('books.index'))
            ->setPriority(0.9)
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY));

        $sitemap->add(Url::create(route('authors.index'))
            ->setPriority(0.9)
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY));

        // 2. Books
        $this->info('Adding Books...');
        $bookCount = 0;
        Book::public()->select('id', 'updated_at')->chunk(500, function ($books) use ($sitemap, &$bookCount) {
            foreach ($books as $book) {
                // Assuming explicit book page or reader page. 
                // Using book.read for now as per routes/web.php. 
                // Adjust if a detail page exists.
                $sitemap->add(
                    Url::create(route('book.read', ['bookId' => $book->id]))
                        ->setLastModificationDate($book->updated_at)
                        ->setPriority(0.8)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                );
            }
            $bookCount += $books->count();
            $this->info("Processed {$bookCount} books...");
        });

        // 3. Authors
        $this->info('Adding Authors...');
        $authorCount = 0;
        Author::select('id', 'updated_at')->chunk(500, function ($authors) use ($sitemap, &$authorCount) {
            foreach ($authors as $author) {
                $sitemap->add(
                    Url::create(route('author.show', ['id' => $author->id]))
                        ->setLastModificationDate($author->updated_at)
                        ->setPriority(0.7)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                );
            }
            $authorCount += $authors->count();
            $this->info("Processed {$authorCount} authors...");
        });

        // Write to file
        $path = public_path('sitemap.xml');
        $sitemap->writeToFile($path);

        $duration = round(microtime(true) - $startTime, 2);
        $this->info("Sitemap generated successfully at {$path}");
        $this->info("Total items: " . (4 + $bookCount + $authorCount));
        $this->info("Duration: {$duration} seconds");
        
        return Command::SUCCESS;
    }
}
