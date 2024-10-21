<?php
/**
 * PDF Advanced Table - User-Friendly Example
 *
 * This script generates a PDF document with an advanced table,
 * demonstrating features like colspan, rowspan, cell alignment, images, etc.
 */

require_once __DIR__ . '/autoload.php';

use App\Fpdf\PdfFactory;
use EvoSys21\PdfLib\Table;

// Define the path to your content directory
//const CONTENT_PATH = __DIR__ . '/content';

// Create a new PDF object
$pdf = PdfFactory::newPdf('table');

// Define some background colors for table cells
$bgColorLightGreen = [234, 255, 218];
$bgColorMintGreen  = [165, 250, 220];
$bgColorCream      = [255, 252, 249];

// Create the Table object
$table = new Table($pdf);

// Set the styles for the table
PdfSettings::setTableStyles($table);

// Load text content for cells
$longText = file_get_contents(CONTENT_PATH . '/table-cell-text.txt');

// Initialize the table with column widths and options
$table->initialize(
    [35, 30, 40, 40, 25], // Column widths
    ['TABLE' => ['TABLE_LEFT_MARGIN' => 0]] // Table options
);

// Define the header row
$header = [
    ['TEXT' => 'Header 1'],
    ['TEXT' => 'Header 2'],
    ['TEXT' => 'Header 3'],
    ['TEXT' => 'Header 4'],
    ['TEXT' => 'Header 5']
];

// Add the header row to the table
$table->addHeader($header);

// Modify the header for demonstrating colspan and rowspan
$header[2] = [
    'TEXT'             => 'Header Colspan/Rowspan',
    'COLSPAN'          => 2,
    'ROWSPAN'          => 2,
    'TEXT_COLOR'       => [10, 20, 100],
    'BACKGROUND_COLOR' => $bgColorMintGreen
];

// Add the modified header to the table
$table->addHeader($header);

// Add an empty header line for spacing
$table->addHeader();

// Initialize variables for the loop
$fontSize = 5;
$colspan  = 2;

// Initialize RGB values for background colors
$rgbValues = ['r' => 255, 'g' => 255, 'b' => 255];

// Function to update RGB values
function updateRgbValues(&$rgbValues, $decrementR, $decrementG, $decrementB, $threshold = 150) {
    $rgbValues['r'] -= $decrementR;
    $rgbValues['g'] -= $decrementG;
    $rgbValues['b'] -= $decrementB;

    foreach ($rgbValues as $color => $value) {
        if ($value < $threshold) {
            $rgbValues[$color] = 255;
        }
    }
}

// Generate table rows
for ($rowIndex = 0; $rowIndex < 45; $rowIndex++) {
    $row = [];

    // Set default cell data for the row
    $row[0] = [
        'TEXT'      => "Row No - $rowIndex",
        'TEXT_SIZE' => $fontSize,
        'BACKGROUND_COLOR' => [255 - $rgbValues['b'], $rgbValues['g'], $rgbValues['r']]
    ];
    $row[1] = [
        'TEXT'             => "Test Text Column 1- $rowIndex",
        'TEXT_SIZE'        => 13 - $fontSize,
        'BACKGROUND_COLOR' => [$rgbValues['r'], $rgbValues['g'], $rgbValues['b']]
    ];
    $row[2] = [
        'TEXT' => "Test Text Column 2- $rowIndex"
    ];
    $row[3] = [
        'TEXT'      => "Longer text, this will split sometimes...",
        'TEXT_SIZE' => 15 - $fontSize
    ];
    $row[4] = [
        'TEXT'      => "Short 4- $rowIndex",
        'TEXT_SIZE' => 7
    ];

    // Special cases for certain rows
    switch ($rowIndex) {
        case 0:
            // First row: Cell spanning across multiple columns
            $row[1] = [
                'TEXT'      => $longText,
                'COLSPAN'   => 4,
                'ALIGN'     => 'C',
                'LINE_SIZE' => 5
            ];
            break;

        case 1:
            // Second row: Different text alignments
            $row[0] = [
                'TEXT'  => "Top Right Align\nAlign Top\nRight Right Align",
                'ALIGN' => 'RT'
            ];

            $row[1] = [
                'TEXT'      => "Middle Center Align Bold Italic",
                'TEXT_TYPE' => 'BI', // Bold Italic
                'ALIGN'     => 'MC'
            ];

            $row[2] = [
                'TEXT'  => "\n\n\n\n\nBottom Left Align",
                'ALIGN' => 'BL'
            ];

            $row[3] = [
                'TEXT'  => "Middle Justified Align Longer text",
                'ALIGN' => 'MJ'
            ];

            $row[4] = [
                'TEXT'        => "TOP RIGHT Align with top padding(5)",
                'ALIGN'       => 'TR',
                'PADDING_TOP' => 5
            ];
            break;

        case 2:
            // Third row: Cell with an image
            $row[1]['TEXT'] = "Cells can be images -->>>";
            $row[2] = [
                'TYPE'  => 'IMAGE',
                'FILE'  => CONTENT_PATH . '/images/dice.jpg',
                'WIDTH' => 15
            ];
            break;
    }

    // Demonstrate colspan in rows 4 to 6
    if ($rowIndex > 3 && $rowIndex < 7) {
        $row[1] = [
            'TEXT'             => "Colspan Example - Center Align",
            'COLSPAN'          => $colspan,
            'BACKGROUND_COLOR' => [$rgbValues['b'], 50, 50],
            'TEXT_COLOR'       => [255, 255, $rgbValues['g']],
            'TEXT_ALIGN'       => 'C'
        ];
        $colspan = ($colspan % 4) + 1; // Cycle colspan between 2 and 4
    }

    // Demonstrate rowspan in row 7
    if ($rowIndex == 7) {
        $row[3] = [
            'TEXT'             => "Rowspan Example",
            'BACKGROUND_COLOR' => [$rgbValues['b'], $rgbValues['b'], 250],
            'ROWSPAN'          => 4
        ];
    }

    // Additional rowspan examples
    if ($rowIndex == 8) {
        $row[1] = [
            'TEXT'             => "Rowspan Example",
            'BACKGROUND_COLOR' => [$rgbValues['b'], 50, 50],
            'ROWSPAN'          => 6
        ];
    }
    if ($rowIndex == 9) {
        $row[2] = [
            'TEXT'             => "Rowspan Example",
            'BACKGROUND_COLOR' => [$rgbValues['r'], $rgbValues['r'], $rgbValues['r']],
            'ROWSPAN'          => 3
        ];
    }

    // Demonstrate combined rowspan and colspan
    if ($rowIndex == 12) {
        $row[2] = [
            'TEXT'             => "Rowspan and Colspan Example\n\nCenter/Middle Alignment",
            'TEXT_ALIGN'       => 'C',
            'VERTICAL_ALIGN'   => 'M',
            'BACKGROUND_COLOR' => $bgColorLightGreen,
            'ROWSPAN'          => 5,
            'COLSPAN'          => 2
        ];
    }

    if ($rowIndex == 17) {
        $row[0] = [
            'TEXT'             => $longText,
            'TEXT_ALIGN'       => 'C',
            'VERTICAL_ALIGN'   => 'M',
            'BACKGROUND_COLOR' => $bgColorLightGreen,
            'ROWSPAN'          => 5,
            'COLSPAN'          => 4
        ];
    }

    // Increment font size within a range
    $fontSize += 0.5;
    if ($fontSize > 10) {
        $fontSize = 5;
    }

    // Modify RGB values for background colors
    updateRgbValues($rgbValues, 20, 5, 10);

    // Add the row to the table
    $table->addRow($row);
}

// Close the table
$table->close();

// Output the PDF to the browser
$pdf->Output();
