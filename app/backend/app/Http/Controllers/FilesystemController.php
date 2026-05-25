<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FilesystemController extends Controller
{
    private const DISK = 'mnt-said-projects';

    public function browse(Request $request): JsonResponse
    {
        $relative = (string) $request->query('path', '');
        $disk     = Storage::disk(self::DISK);

        if (! $disk->exists($relative)) {
            return response()->json(['error' => 'Directory not found.'], 404);
        }

        $items = [];
        foreach ($disk->directories($relative) as $dir) {
            $items[] = [
                'name'       => basename($dir),
                'path'       => $dir,
                'selectable' => true,
            ];
        }

        usort($items, fn($a, $b) => strcasecmp($a['name'], $b['name']));

        return response()->json([
            'path'        => $relative,
            'full_path'   => $disk->path($relative),
            'breadcrumbs' => $this->buildBreadcrumbs($relative),
            'items'       => $items,
        ]);
    }

    public function createDirectory(Request $request): JsonResponse
    {
        $relative = (string) $request->input('path', '');
        $name     = $request->input('name', '');

        if ($name === '' || preg_match('/[\/\\\\]/', $name)) {
            return response()->json(['error' => 'Invalid folder name.'], 422);
        }

        $disk      = Storage::disk(self::DISK);
        $childPath = $relative === '' ? $name : $relative . '/' . $name;

        if ($disk->exists($childPath)) {
            return response()->json(['error' => 'A file or folder with that name already exists.'], 409);
        }

        if (! $disk->makeDirectory($childPath)) {
            return response()->json(['error' => 'Could not create directory.'], 500);
        }

        return response()->json([
            'name'       => $name,
            'path'       => $childPath,
            'selectable' => true,
        ], 201);
    }

    private function buildBreadcrumbs(string $relative): array
    {
        if ($relative === '') {
            return [['label' => 'Projects root', 'path' => '']];
        }

        $parts    = explode('/', trim($relative, '/'));
        $crumbs   = [['label' => 'Projects root', 'path' => '']];
        $accumulated = '';

        foreach ($parts as $part) {
            $accumulated = $accumulated === '' ? $part : $accumulated . '/' . $part;
            $crumbs[] = ['label' => $part, 'path' => $accumulated];
        }

        return $crumbs;
    }
}
