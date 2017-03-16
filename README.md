# document-data-extractor
Reads documents from different formats and extracts data out of it.

## Example
```php
$reader = new DelegateReader();
$reader
  ->addReader(new PdfReader())
  ->addReader(new GoogleVisionImageReader('path/to/credentials.json', new PhotoImageOptimizer()));
  
$text = $reader->read('some/file.jpg', 'eng');

$analyzer = new AnalyzerManager();
$analyzer
  ->addAnalyzer(new IBANAnalyzer())
  ->addAnaylzer(new EmailAnaylzer())
  ->addAnalyzer(new DateAnalyzer())
  ->addAnaylzer(new ESRAnalyzer());
  
$data = $analyzer->analyze($text);

var_dump($text, $data);
```
