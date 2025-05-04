# class MDSite extends JsonMap НАЧАЛО


```PHP
class MDSite extends JsonMap {
    /**
     * Получить текущую конфигурацию
     */
    public static function getConfig(): array {
        return [
            'excludedDirs' => self::$excludeDirs,
            'filePattern' => self::$filePattern
        ];
    }
    
    /**
     * Получить содержимое content.json
     * @return array|null Древовидная структура контента или null
     */
    public static function getContent(): ?array {
        $file = self::findRootDir() . '/content.json';
        return self::loadJsonFile($file);
    }
    
    /**
     * Получить содержимое index.json 
     * @return array|null Плоский список файлов или null
     */
    public static function getIndex(): ?array {
        $file = self::findRootDir() . '/index.json';
        return self::loadJsonFile($file);
    }
    
    /**
     * Общий метод для загрузки JSON файлов
     */
    private static function loadJsonFile(string $file): ?array {
        if (!file_exists($file)) {
            return null;
        }
        
        $content = file_get_contents($file);
        if ($content === false) {
            return null;
        }
        
        $data = json_decode($content, true);
        return is_array($data) ? $data : null;
    }
	
	
}



```
