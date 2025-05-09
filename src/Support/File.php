<?php

namespace Scriptmancer\Support;

class File
{
    /**
     * Determine if a file or directory exists.
     *
     * @param string $path
     * @return bool
     */
    public static function exists(string $path): bool
    {
        return file_exists($path);
    }

    /**
     * Get the contents of a file.
     *
     * @param string $path
     * @param bool $lock
     * @return string|false
     */
    public static function get(string $path, bool $lock = false)
    {
        if (static::exists($path)) {
            return $lock ? file_get_contents($path, LOCK_SH) : file_get_contents($path);
        }

        return false;
    }

    /**
     * Write the contents of a file.
     *
     * @param string $path
     * @param string $contents
     * @param bool $lock
     * @return int|false
     */
    public static function put(string $path, string $contents, bool $lock = false)
    {
        return $lock
            ? file_put_contents($path, $contents, LOCK_EX)
            : file_put_contents($path, $contents);
    }

    /**
     * Append to a file.
     *
     * @param string $path
     * @param string $data
     * @return int|false
     */
    public static function append(string $path, string $data)
    {
        return file_put_contents($path, $data, FILE_APPEND | LOCK_EX);
    }

    /**
     * Prepend to a file.
     *
     * @param string $path
     * @param string $data
     * @return int|false
     */
    public static function prepend(string $path, string $data)
    {
        if (static::exists($path)) {
            return static::put($path, $data . static::get($path));
        }

        return static::put($path, $data);
    }

    /**
     * Delete the file at a given path.
     *
     * @param string|array $paths
     * @return bool
     */
    public static function delete($paths): bool
    {
        $paths = is_array($paths) ? $paths : func_get_args();

        $success = true;

        foreach ($paths as $path) {
            try {
                if (!@unlink($path)) {
                    $success = false;
                }
            } catch (\Throwable $e) {
                $success = false;
            }
        }

        return $success;
    }

    /**
     * Move a file to a new location.
     *
     * @param string $path
     * @param string $target
     * @return bool
     */
    public static function move(string $path, string $target): bool
    {
        return rename($path, $target);
    }

    /**
     * Copy a file to a new location.
     *
     * @param string $path
     * @param string $target
     * @return bool
     */
    public static function copy(string $path, string $target): bool
    {
        return copy($path, $target);
    }

    /**
     * Create a directory.
     *
     * @param string $path
     * @param int $mode
     * @param bool $recursive
     * @param bool $force
     * @return bool
     */
    public static function makeDirectory(string $path, int $mode = 0755, bool $recursive = false, bool $force = false): bool
    {
        if ($force) {
            return @mkdir($path, $mode, $recursive);
        }

        return mkdir($path, $mode, $recursive);
    }

    /**
     * Get the file size.
     *
     * @param string $path
     * @return int
     */
    public static function size(string $path): int
    {
        return filesize($path);
    }

    /**
     * Get the file's last modification time.
     *
     * @param string $path
     * @return int
     */
    public static function lastModified(string $path): int
    {
        return filemtime($path);
    }

    /**
     * Get the file type.
     *
     * @param string $path
     * @return string|false
     */
    public static function type(string $path)
    {
        return filetype($path);
    }

    /**
     * Get the mime type of a file.
     *
     * @param string $path
     * @return string|false
     */
    public static function mimeType(string $path)
    {
        return mime_content_type($path);
    }

    /**
     * Get the file extension.
     *
     * @param string $path
     * @return string
     */
    public static function extension(string $path): string
    {
        return pathinfo($path, PATHINFO_EXTENSION);
    }

    /**
     * Get the file name without extension.
     *
     * @param string $path
     * @return string
     */
    public static function name(string $path): string
    {
        return pathinfo($path, PATHINFO_FILENAME);
    }

    /**
     * Get the file name with extension.
     *
     * @param string $path
     * @return string
     */
    public static function basename(string $path): string
    {
        return pathinfo($path, PATHINFO_BASENAME);
    }

    /**
     * Get the directory name of the path.
     *
     * @param string $path
     * @return string
     */
    public static function dirname(string $path): string
    {
        return pathinfo($path, PATHINFO_DIRNAME);
    }

    /**
     * Check if a file is readable.
     *
     * @param string $path
     * @return bool
     */
    public static function isReadable(string $path): bool
    {
        return is_readable($path);
    }

    /**
     * Check if a file is writable.
     *
     * @param string $path
     * @return bool
     */
    public static function isWritable(string $path): bool
    {
        return is_writable($path);
    }

    /**
     * Check if a file is a directory.
     *
     * @param string $path
     * @return bool
     */
    public static function isDirectory(string $path): bool
    {
        return is_dir($path);
    }

    /**
     * Check if a file is a file.
     *
     * @param string $path
     * @return bool
     */
    public static function isFile(string $path): bool
    {
        return is_file($path);
    }

    /**
     * Find path names matching a pattern.
     *
     * @param string $pattern
     * @param int $flags
     * @return array|false
     */
    public static function glob(string $pattern, int $flags = 0)
    {
        return glob($pattern, $flags);
    }

    /**
     * Get a human-readable file size.
     *
     * @param int $bytes
     * @param int $precision
     * @return string
     */
    public static function humanReadableSize(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        
        $bytes /= (1 << (10 * $pow));
        
        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    /**
     * Get an array of all files in a directory.
     *
     * @param string $directory
     * @param bool $recursive
     * @return array
     */
    public static function files(string $directory, bool $recursive = false): array
    {
        $result = [];
        
        if (!static::isDirectory($directory)) {
            return $result;
        }
        
        $handle = opendir($directory);
        
        if ($handle === false) {
            return $result;
        }
        
        while (($file = readdir($handle)) !== false) {
            // Skip dots
            if ($file === '.' || $file === '..') {
                continue;
            }
            
            $path = $directory . DIRECTORY_SEPARATOR . $file;
            
            if (static::isDirectory($path) && $recursive) {
                $result = array_merge($result, static::files($path, true));
            } elseif (static::isFile($path)) {
                $result[] = $path;
            }
        }
        
        closedir($handle);
        
        return $result;
    }

    /**
     * Get an array of all directories in a directory.
     *
     * @param string $directory
     * @param bool $recursive
     * @return array
     */
    public static function directories(string $directory, bool $recursive = false): array
    {
        $result = [];
        
        if (!static::isDirectory($directory)) {
            return $result;
        }
        
        $handle = opendir($directory);
        
        if ($handle === false) {
            return $result;
        }
        
        while (($file = readdir($handle)) !== false) {
            // Skip dots
            if ($file === '.' || $file === '..') {
                continue;
            }
            
            $path = $directory . DIRECTORY_SEPARATOR . $file;
            
            if (static::isDirectory($path)) {
                $result[] = $path;
                
                if ($recursive) {
                    $result = array_merge($result, static::directories($path, true));
                }
            }
        }
        
        closedir($handle);
        
        return $result;
    }
} 