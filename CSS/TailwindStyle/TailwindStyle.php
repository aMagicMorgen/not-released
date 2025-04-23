class TailwindStyle {
    private $htmlPath;
    private $dom;
    private $originalToNormalized = [];
    private $normalizedToKey = [];
    private $usedKeys = [];

    public function __construct($htmlPath) {
        $this->htmlPath = $htmlPath;
        $this->dom = new DOMDocument();
    }

    public function process() {
        $this->loadHtml();
        $this->normalizeClasses();
        $this->generateUniqueKeys();
        $this->replaceWithKeys();
        $this->addMappingComment();
        $this->saveHtml();
    }

    private function loadHtml() {
        @$this->dom->loadHTMLFile($this->htmlPath, LIBXML_NOERROR | LIBXML_NOWARNING);
    }

    private function normalizeClasses() {
        $xpath = new DOMXPath($this->dom);
        $elements = $xpath->query('//*[@class]');

        foreach ($elements as $element) {
            $original = $element->getAttribute('class');
            $normalized = $this->normalizeClassString($original);
            $this->originalToNormalized[$original] = $normalized;
            $element->setAttribute('class', $normalized);
        }
    }

    private function normalizeClassString($classString) {
        return implode(' ', array_filter(
            explode(' ', preg_replace('/\s+/', ' ', trim($classString)))
        );
    }

    private function generateUniqueKeys() {
        $uniqueNormalized = array_unique(array_values($this->originalToNormalized));
        foreach ($uniqueNormalized as $classStr) {
            $this->normalizedToKey[$classStr] = $this->generateShortKey($classStr);
        }
    }

    private function generateShortKey() {
        static $index = 0;
        $key = "tw-" . $index++;
        return $key;
    }

    private function replaceWithKeys() {
        $xpath = new DOMXPath($this->dom);
        $elements = $xpath->query('//*[@class]');

        foreach ($elements as $element) {
            $currentClass = $element->getAttribute('class');
            if (isset($this->normalizedToKey[$currentClass])) {
                $element->setAttribute('class', $this->normalizedToKey[$currentClass]);
            }
        }
    }

    private function addMappingComment() {
        $mapping = [];
        foreach ($this->normalizedToKey as $classStr => $key) {
            $mapping[] = "{$key} {{$classStr}}";
        }

        $comment = $this->dom->createComment(
            "mcss\n" . implode("\n", $mapping) . "\n/mcss"
        );

        if ($body = $this->dom->getElementsByTagName('body')->item(0)) {
            $body->appendChild($comment);
        } else {
            $this->dom->appendChild($comment);
        }
    }

    private function saveHtml() {
        $newHtmlPath = preg_replace('/\.html$/', '-mcss.html', $this->htmlPath);
        $this->dom->saveHTMLFile($newHtmlPath);
    }
}
