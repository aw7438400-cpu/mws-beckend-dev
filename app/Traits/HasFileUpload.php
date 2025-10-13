<?php

namespace App\Traits;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

trait HasFileUpload
{
    protected $parentModel;

    protected $relationModel;

    public function setModel($parentModel, $relationModel = null)
    {
        $this->parentModel = $parentModel;

        $this->relationModel = $relationModel;
    }

    public function createWithFileUpload(array $data, string $fieldFileName)
    {
        if (isset($data[$fieldFileName])) {
            $file = $data[$fieldFileName];

            $filePaths = $this->storeFiles([$file], $fieldFileName, $this->parentModel);

            $data['file_path'] = $filePaths[0];

            $data[$fieldFileName] = $data['file_path'];
        }
        return $this->parentModel->create($data);
    }

    public function createWithFileUploadMultiple(array $data, string $foreignKeyName, string $fieldFileName)
    {
        $parentModel = $this->parentModel->create($data);

        if (isset($data[$fieldFileName])) {
            $files = $data[$fieldFileName];

            $filePaths = $this->storeFiles($files, $fieldFileName, $this->relationModel);

            foreach ($filePaths as $filePath) {
                $this->relationModel->create([
                    $foreignKeyName => $parentModel->id,
                    $fieldFileName => $filePath
                ]);
            }
        }

        return $parentModel;
    }

    public function updateWithFileUploadId(int $id, array $data, string $fieldFileName)
    {
        $parentModel = $this->parentModel->findOrFail($id);

        if (isset($data[$fieldFileName])) {
            $files = $data[$fieldFileName];

            if ($parentModel->{$fieldFileName}) {
                if (!is_null($parentModel->$fieldFileName) && Storage::exists($parentModel->{$fieldFileName})) {
                    Storage::delete($parentModel->{$fieldFileName});
                }
            }

            $filePaths = $this->storeFiles([$files], $fieldFileName, $this->parentModel);

            $data[$fieldFileName] = $filePaths[0];
        }

        $parentModel->update($data);

        return $parentModel;
    }

    public function updateWithFileUploadUuid(string $uuid, array $data, string $fieldFileName)
    {
        $parentModel = $this->findByUuidParent($uuid);

        if (isset($data[$fieldFileName])) {
            $files = $data[$fieldFileName];

            if (!is_null($parentModel->$fieldFileName) && $parentModel->{$fieldFileName}) {
                Storage::delete($parentModel->{$fieldFileName});
            }

            $filePaths = $this->storeFiles([$files], $fieldFileName, $this->parentModel);

            $data[$fieldFileName] = $filePaths[0];
        }

        $parentModel->update($data);

        return $parentModel;
    }

    public function updateWithFileUploadMultipleId(int $id, array $data, string $foreignKeyName, string $fieldFileName)
    {
        $parentModel = $this->parentModel->findOrFail($id);
        $parentModel->update($data);

        if (isset($data[$fieldFileName])) {
            $files = $data[$fieldFileName];

            $existingMedia = $this->relationModel::where($foreignKeyName, $id)->get();
            foreach ($existingMedia as $media) {
                if (!is_null($media->$fieldFileName) && Storage::exists($media->$fieldFileName)) {
                    Storage::delete($media->$fieldFileName);
                }
                $media->delete();
            }

            $filePaths = $this->storeFiles($files, $fieldFileName, $this->relationModel);

            foreach ($filePaths as $filePath) {
                $this->relationModel->create([
                    $foreignKeyName => $parentModel->id,
                    $fieldFileName => $filePath
                ]);
            }
        }

        return $parentModel;
    }

    public function updateWithFileUploadMultipleUuid(string $uuid, array $data, string $foreignKeyName, string $fieldFileName)
    {
        $parentModel = $this->findByUuidParent($uuid);

        $parentModel->update($data);

        if (isset($data[$fieldFileName])) {
            $files = $data[$fieldFileName];

            $existingMedia = $this->relationModel::where($foreignKeyName, $parentModel->id)->get();
            foreach ($existingMedia as $media) {
                if (!is_null($media->$fieldFileName) && Storage::exists($media->$fieldFileName)) {
                    Storage::delete($media->$fieldFileName);
                }
                $media->delete();
            }

            $filePaths = $this->storeFiles($files, $fieldFileName, $this->relationModel);

            foreach ($filePaths as $filePath) {
                $this->relationModel->create([
                    $foreignKeyName => $parentModel->id,
                    $fieldFileName => $filePath
                ]);
            }
        }

        return $parentModel;
    }

    public function destroyWithFileMultipleId(int $id, string $foreignKeyName, string $fieldFileName)
    {
        $parentModel = $this->parentModel::findOrFail($id);

        if (!$parentModel) {
            return response()->json(['message' => 'Model not found'], 404);
        }

        $existingMedia = $this->relationModel::where($foreignKeyName, $parentModel->id)->get();

        foreach ($existingMedia as $media) {
            if (!is_null($media->$fieldFileName) && Storage::exists($media->$fieldFileName)) {
                Storage::delete($media->$fieldFileName);
            }
            $media->delete();
        }

        $parentModel->delete();
    }

    public function destroyWithFileMultipleUuid(string $uuid, string $foreignKeyName, string $fieldFileName)
    {
        $parentModel = $this->findByUuidParent($uuid);

        if (!$parentModel) {
            return response()->json(['message' => 'Model not found'], 404);
        }

        $existingMedia = $this->relationModel::where($foreignKeyName, $parentModel->id)->get();

        foreach ($existingMedia as $media) {
            if (!is_null($media->$fieldFileName) && Storage::exists($media->$fieldFileName)) {
                Storage::delete($media->$fieldFileName);
            }
            $media->delete();
        }

        $parentModel->delete();
    }

    public function destroyWithFileId(int $id, string $fieldFileName)
    {
        $data = $this->parentModel->findOrFail($id);

        if (!is_null($data->$fieldFileName) && Storage::exists($data->$fieldFileName)) {
            Storage::delete($data->$fieldFileName);
        }

        return $data->delete();
    }

    public function destroyWithFileUuid(string $uuid, string $fieldFileName)
    {
        $data = $this->findByUuidParent($uuid);

        if (!is_null($data->$fieldFileName) && Storage::exists($data->$fieldFileName)) {
            Storage::delete($data->$fieldFileName);
        }

        return $data->delete();
    }

    protected function findByUuidParent(string $uuid)
    {
        $model = $this->parentModel->where('uuid', $uuid)->first();

        if (!$model) {
            return $this->notFound();
        }

        return $model;
    }

    protected function storeFiles($files, $fieldFileName, $modelName)
    {
        $modelName = class_basename($modelName);

        $directory = 'public/uploads/' . Str::plural(strtolower($modelName));

        $filePaths = [];

        foreach ($files as $file) {
            $filename = uniqid() . '-' . $fieldFileName . '-' . str_replace(' ', '_', $file->getClientOriginalName());
            Storage::putFileAs($directory, $file, $filename);
            $filePaths[] = $directory . '/' . $filename;
        }

        return $filePaths;
    }

    protected function storeFileStudyCenter($files, $school, $year, $subject, $modelName, $classroom, $title)
    {
        $modelName = class_basename($modelName);

        $directory = 'public/uploads/' . $school . '/' . $year . '/' . $subject . '/' . Str::plural(strtolower($modelName)) . '/' . $classroom;

        $filePaths = [];

        foreach ($files as $file) {
            $filename = uniqid() . '-' . $title . '-' . str_replace(' ', '_', $file->getClientOriginalName());
            Storage::putFileAs($directory, $file, $filename);
            $filePaths[] = $directory . '/' . $filename;
        }

        return $filePaths;
    }
}
