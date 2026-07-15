<?php
header('Content-Type: application/json');
require_once __DIR__ . '/autoload.php';

use Melbahja\Seo\Schema;
use Melbahja\Seo\Schema\Thing;
use Melbahja\Seo\Schema\Organization;
use Melbahja\Seo\Schema\Place\LocalBusiness;
use Melbahja\Seo\Schema\CreativeWork\WebPage;
use Melbahja\Seo\MetaTags;
use Melbahja\Seo\Sitemap;
use Melbahja\Seo\Sitemap\OutputMode;
use Melbahja\Seo\Validation\SchemaValidator;
use Melbahja\Seo\Validation\RobotsValidator;

$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    echo json_encode(['error' => 'Invalid JSON input']);
    exit;
}

$action = $input['action'] ?? '';

switch ($action) {
    case 'schema':
        $type = $input['type'] ?? 'Thing';
        $props = $input['props'] ?? [];
        
        try {
            if ($type === 'Organization') {
                $item = new Organization($props);
                $classUsed = 'Organization';
            } elseif ($type === 'LocalBusiness') {
                $item = new LocalBusiness($props);
                $classUsed = 'LocalBusiness';
            } elseif ($type === 'WebPage') {
                $item = new WebPage($props);
                $classUsed = 'WebPage';
            } else {
                $item = new Thing(type: $type, props: $props);
                $classUsed = 'Thing';
            }
            
            $errors = SchemaValidator::validate($item);
            
            $schemaObj = new Schema($item);
            $outputHtml = (string) $schemaObj;
            
            $phpProps = var_export($props, true);
            $phpProps = str_replace("\n", "\n    ", $phpProps);
            
            if ($classUsed === 'Thing') {
                $phpCode = "use Melbahja\\Seo\\Schema;\nuse Melbahja\\Seo\\Schema\\Thing;\n\n\$schema = new Schema(\n    new Thing(type: '{$type}', props: {$phpProps})\n);\n\necho \$schema;";
            } else {
                $phpCode = "use Melbahja\\Seo\\Schema;\nuse Melbahja\\Seo\\Schema\\{$classUsed};\n\n\$schema = new Schema(\n    new {$classUsed}({$phpProps})\n);\n\necho \$schema;";
            }
            
            echo json_encode([
                'success' => true,
                'php' => $phpCode,
                'output' => $outputHtml,
                'errors' => $errors
            ]);
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        break;
        
    case 'metatags':
        $title = $input['title'] ?? '';
        $desc = $input['description'] ?? '';
        $canonical = $input['canonical'] ?? '';
        $image = $input['image'] ?? '';
        $robots = $input['robots'] ?? [];
        $twitter = $input['twitter'] ?? '';
        $og = $input['og'] ?? '';
        
        try {
            $metatags = new MetaTags();
            $phpCodeParts = [];
            $phpCodeParts[] = "\$metatags = new MetaTags();";
            
            if ($title !== '') {
                $metatags->title($title);
                $phpCodeParts[] = "->title('" . addslashes($title) . "')";
            }
            if ($desc !== '') {
                $metatags->description($desc);
                $phpCodeParts[] = "->description('" . addslashes($desc) . "')";
            }
            if ($canonical !== '') {
                $metatags->canonical($canonical);
                $phpCodeParts[] = "->canonical('" . addslashes($canonical) . "')";
            }
            if ($image !== '') {
                $metatags->image($image);
                $phpCodeParts[] = "->image('" . addslashes($image) . "')";
            }
            if (!empty($robots)) {
                $metatags->robots($robots);
                $robotsExport = var_export($robots, true);
                $phpCodeParts[] = "->robots({$robotsExport})";
            }
            if ($twitter !== '') {
                $metatags->twitter('card', 'summary_large_image')
                         ->twitter('creator', $twitter);
                $phpCodeParts[] = "->twitter('card', 'summary_large_image')";
                $phpCodeParts[] = "->twitter('creator', '" . addslashes($twitter) . "')";
            }
            if ($og !== '') {
                $metatags->og('type', $og);
                $phpCodeParts[] = "->og('type', '" . addslashes($og) . "')";
            }
            
            $outputHtml = (string) $metatags;
            
            $phpCode = "use Melbahja\\Seo\\MetaTags;\n\n" . implode("\n        ", $phpCodeParts) . ";\n\necho \$metatags;";
            
            echo json_encode([
                'success' => true,
                'php' => $phpCode,
                'output' => $outputHtml
            ]);
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        break;
        
    case 'sitemap':
        $baseUrl = $input['baseUrl'] ?? 'https://example.com';
        $links = $input['links'] ?? [];
        
        try {
            $sitemap = new Sitemap($baseUrl, mode: OutputMode::MEMORY);
            
            $sitemap->links('blog.xml', function($map) use ($links) {
                foreach ($links as $link) {
                    $loc = $link['loc'] ?? '';
                    if ($loc === '') continue;
                    
                    $builder = $map->loc($loc);
                    if (isset($link['priority']) && $link['priority'] !== '') {
                        $builder->priority((float) $link['priority']);
                    }
                    if (!empty($link['changeFreq'])) {
                        $builder->changeFreq($link['changeFreq']);
                    }
                    if (!empty($link['lastMod'])) {
                        $builder->lastMod($link['lastMod']);
                    }
                    if (!empty($link['image'])) {
                        // pass images option
                    }
                }
            });
            
            // Build rendering
            $indexXml = $sitemap->render();
            $blogXml = $sitemap->generate('blog.xml')->render();
            
            $phpLinksStr = "";
            foreach ($links as $link) {
                $loc = addslashes($link['loc'] ?? '');
                $priority = $link['priority'] ?? '';
                $freq = $link['changeFreq'] ?? '';
                $lastmod = $link['lastMod'] ?? '';
                
                $phpLinksStr .= "            ->loc('{$loc}')\n";
                if ($priority !== '') $phpLinksStr .= "            ->priority({$priority})\n";
                if ($freq) $phpLinksStr .= "            ->changeFreq('{$freq}')\n";
                if ($lastmod) $phpLinksStr .= "            ->lastMod('{$lastmod}')\n";
            }
            
            $phpCode = "use Melbahja\\Seo\\Sitemap;\nuse Melbahja\\Seo\\Sitemap\\OutputMode;\n\n\$sitemap = new Sitemap(\n    baseUrl: '{$baseUrl}',\n    mode: OutputMode::MEMORY\n);\n\n\$sitemap->links('blog.xml', function(\$map) {\n    \$map\n{$phpLinksStr}    ;\n});\n\n\$indexXml = \$sitemap->render();\n\$blogXml = \$sitemap->generate('blog.xml')->render();";
            
            echo json_encode([
                'success' => true,
                'php' => $phpCode,
                'output' => [
                    'sitemap.xml' => $indexXml,
                    'blog.xml' => $blogXml
                ]
            ]);
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        break;
        
    case 'validate':
        $type = $input['type'] ?? '';
        $content = $input['content'] ?? '';
        
        try {
            $errors = null;
            if ($type === 'schema') {
                // Strip HTML script tags if present
                $cleanedContent = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '$1', $content);
                $cleanedContent = preg_replace('/^\s*<script\b[^>]*>/i', '', $cleanedContent);
                $cleanedContent = preg_replace('/<\/script>\s*$/i', '', $cleanedContent);
                $cleanedContent = trim($cleanedContent);

                $data = json_decode($cleanedContent, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $errors = ['Invalid JSON format: ' . json_last_error_msg()];
                } else {
                    $errors = SchemaValidator::validate($data);
                }
            } elseif ($type === 'robots') {
                $errors = RobotsValidator::validate($content);
            } else {
                throw new \Exception('Unknown validation type');
            }
            
            echo json_encode([
                'success' => true,
                'errors' => $errors
            ]);
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        break;
        
    default:
        echo json_encode(['error' => 'Unknown action']);
        break;
}
