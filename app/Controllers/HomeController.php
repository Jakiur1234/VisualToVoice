<?php
namespace App\Controllers;

use Spatie\PdfToImage\Pdf;
use thiagoalessio\TesseractOCR\TesseractOCR;

class HomeController extends Controllers
{
    public function index()
    {
        return view('welcome');
    }

    public function process() {
        $message = '';
        $text = '';
        $startPage = 20;
        $endPage = 0;
        $language = '';
        $book = '';

        if (isset($_POST['startPage'])) {
            $startPage = (int)$_POST['startPage'];
        }

        if (isset($_POST['endPage'])) {
            $endPage = (int)$_POST['endPage'];
        }

        if (isset($_POST['language'])) {
            $language = $_POST['language'];
        }

        if (isset($_FILES['book'])) {
            $book = $_FILES['book'];

            if ($book['error'] === UPLOAD_ERR_OK) {
                $fileExtension = pathinfo($book['name'], PATHINFO_EXTENSION);
                $filePath = 'books/' . basename($book['name']);

                if (move_uploaded_file($book['tmp_name'], $filePath)) {
                    $message = 'File uploaded successfully';

                    if ($fileExtension === 'pdf') {
                        $this->processPdf($filePath, $startPage, $endPage, $language, $text, $message);
                    } else {
                        $this->processImage($filePath, $language, $text, $message);
                    }
                } else {
                    $message = 'Failed to upload file';
                }
            } else {
                $message = 'No file uploaded or upload error';
            }
        }

        return view('welcome', [
            'message' => $message,
            'text' => $text,
            'startPage' => $startPage,
            'endPage' => $endPage,
            'language' => $language,
            'book' => $book,
            'is_speechable' => true
        ]);
    }

    private function processImage($filePath, $language, &$text, &$message) {
        try {
            $ocr = new TesseractOCR($filePath);
            $text = $ocr->lang($language)->run();
            
            $message = 'Image processing completed successfully.';
            
            unlink($filePath);
        } catch (\Exception $e) {
            error_log('OCR Processing Failed: ' . $e->getMessage());
            $text = 'Error: ' . $e->getMessage();
            $message = 'Image processing failed';
        }
    }

    private function processPdf($filePath, $startPage, $endPage, $language, &$text, &$message) {
        try {
            $pdf = new Pdf($filePath);
            $pages = $pdf->pageCount();
            $text = '';

            if (($endPage > 0) && ($endPage <= $pages)) {
                $pages = $endPage;
            }

            if ($startPage > $pages) {
                $message = "Start page exceeds total pages in the PDF.";
                return;
            }

            for ($page = $startPage; $page <= $pages; $page++) {
                $imagePath = 'books/page_' . $page . '.jpg';
                $pdf->selectPage($page)->save($imagePath);

                $ocr = new TesseractOCR($imagePath);
                $text .= $ocr->lang($language)->run();

                unlink($imagePath);
            }

            $message = 'PDF processing completed successfully.';
            unlink($filePath);
        } catch (\Exception $e) {
            error_log('PDF Processing Failed: ' . $e->getMessage());
            $text = 'Error: ' . $e->getMessage();
            $message = 'PDF processing failed';
        }
    }
}
