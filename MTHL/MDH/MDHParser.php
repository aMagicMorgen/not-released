<?php
//Класс MDHParser для Markdown Hybrid Text
/*
//ПРИМЕР ИСПОЛЬЗОВАНИЯ
// Initialize the parser
$mdh = new MDHParser();

// Parse a string
$html = $mdh->parseString($yourMdhContent);

// Or parse a file
$html = $mdh->parseFile('path/to/your/file.mdh');

// Register custom parsers
$mdh->registerParser('CUSTOM', function($content, $params) {
    return "<div class='custom'>" . htmlspecialchars($content) . "</div>";
});

*/
class MDHParser {
    private $parsers = [];
    
    /**
     * Register a custom parser for a specific block type
     * @param string $blockType The block type (e.g., 'MDS', 'MCSS', 'PUG')
     * @param callable $parserCallback A function that takes the block content and returns parsed output
     */
    public function registerParser(string $blockType, callable $parserCallback): void {
        $this->parsers[strtoupper($blockType)] = $parserCallback;
    }
    
    /**
     * Parse MDH content from a string
     * @param string $content The MDH content to parse
     * @return string The resulting HTML
     */
    public function parseString(string $content): string {
        $blocks = $this->extractBlocks($content);
        return $this->processBlocks($blocks);
    }
    
    /**
     * Parse MDH content from a file
     * @param string $filePath Path to the MDH file
     * @return string The resulting HTML
     */
    public function parseFile(string $filePath): string {
        $content = file_get_contents($filePath);
        return $this->parseString($content);
    }
    
    /**
     * Extract all blocks from the content
     * @param string $content The MDH content
     * @return array Array of blocks in order of appearance
     */
    private function extractBlocks(string $content): array {
        $pattern = '/<!--\s*([A-Z]+)\s*(.*?)\s*-->(.*?)\s*<!--\s*\/\1\s*-->/is';
        preg_match_all($pattern, $content, $matches, PREG_SET_ORDER);
        
        $blocks = [];
        $lastPos = 0;
        
        foreach ($matches as $match) {
            // Add raw content before this block
            $preContent = substr($content, $lastPos, $match[0][1] - $lastPos);
            if (trim($preContent) !== '') {
                $blocks[] = [
                    'type' => 'RAW',
                    'content' => $preContent
                ];
            }
            
            // Add the block
            $blocks[] = [
                'type' => strtoupper($match[1]),
                'content' => $match[3],
                'params' => $match[2]
            ];
            
            $lastPos = $match[0][1] + strlen($match[0][0]);
        }
        
        // Add remaining content after last block
        $postContent = substr($content, $lastPos);
        if (trim($postContent) !== '') {
            $blocks[] = [
                'type' => 'RAW',
                'content' => $postContent
            ];
        }
        
        return $blocks;
    }
    
    /**
     * Process all blocks through their respective parsers
     * @param array $blocks Array of blocks
     * @return string Combined HTML output
     */
    private function processBlocks(array $blocks): string {
        $output = '';
        
        foreach ($blocks as $block) {
            switch ($block['type']) {
                case 'RAW':
                    $output .= $block['content'];
                    break;
                    
                case 'MDS':
                    $output .= $this->parseMarkdown($block['content']);
                    break;
                    
                case 'MCSS':
                    $output .= $this->parseMCSS($block['content']);
                    break;
                    
                case 'PUG':
                    $output .= $this->parsePug($block['content']);
                    break;
                    
                default:
                    if (isset($this->parsers[$block['type']])) {
                        $output .= call_user_func($this->parsers[$block['type']], $block['content'], $block['params']);
                    } else {
                        // Unknown block type - include as raw
                        $output .= $block['content'];
                    }
            }
        }
        
        return $output;
    }
    
    /**
     * Default Markdown parser
     * @param string $content Markdown content
     * @return string HTML
     */
    private function parseMarkdown(string $content): string {
        // Use Parsedown or another Markdown parser
        $parsedown = new Parsedown();
        return $parsedown->text($content);
    }
    
    /**
     * Default MCSS parser (example - would need implementation)
     * @param string $content MCSS content
     * @return string CSS
     */
    private function parseMCSS(string $content): string {
        // Implement your MCSS parsing logic here
        return '<style>' . $content . '</style>';
    }
    
    /**
     * Default PUG parser (example - would need implementation)
     * @param string $content PUG content
     * @return string HTML
     */
    private function parsePug(string $content): string {
        // Implement PUG parsing or use a library
        return $content; // Placeholder
    }
}

