
import React, { useCallback, useState } from 'react';
import { UploadCloudIcon, LoaderIcon } from './Icons.tsx';

interface FileUploadProps {
    onFileUpload: (files: File[]) => void;
    isLoading: boolean;
}

export const FileUpload: React.FC<FileUploadProps> = ({ onFileUpload, isLoading }) => {
    const [isDragging, setIsDragging] = useState(false);

    const handleDrag = useCallback((e: React.DragEvent<HTMLDivElement>) => {
        e.preventDefault();
        e.stopPropagation();
        if (isLoading) return;
        if (e.type === 'dragenter' || e.type === 'dragover') {
            setIsDragging(true);
        } else if (e.type === 'dragleave') {
            setIsDragging(false);
        }
    }, [isLoading]);

    const handleDrop = useCallback((e: React.DragEvent<HTMLDivElement>) => {
        e.preventDefault();
        e.stopPropagation();
        if (isLoading) return;
        setIsDragging(false);
        if (e.dataTransfer.files && e.dataTransfer.files.length > 0) {
            const uploadedFiles = Array.from(e.dataTransfer.files);
            onFileUpload(uploadedFiles);
            e.dataTransfer.clearData();
        }
    }, [onFileUpload, isLoading]);

    const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        if (isLoading) return;
        if (e.target.files && e.target.files.length > 0) {
            const uploadedFiles = Array.from(e.target.files);
            onFileUpload(uploadedFiles);
             // Reset file input to allow uploading the same file again
            e.target.value = '';
        }
    };
    
    return (
        <div 
            className={`relative border-2 border-dashed rounded-lg p-6 text-center transition-colors duration-200 ease-in-out
            ${isDragging ? 'border-blue-500 bg-blue-50' : 'border-slate-300'}
            ${isLoading ? 'cursor-not-allowed bg-slate-100' : 'hover:border-blue-400 cursor-pointer'}`}
            onDragEnter={handleDrag}
            onDragLeave={handleDrag}
            onDragOver={handleDrag}
            onDrop={handleDrop}
        >
            <input
                type="file"
                id="file-upload"
                multiple
                accept="image/png, image/jpeg, image/webp"
                className="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                onChange={handleChange}
                disabled={isLoading}
            />
            <label htmlFor="file-upload" className={`flex flex-col items-center justify-center space-y-2 ${isLoading ? 'cursor-not-allowed' : 'cursor-pointer'}`}>
                {isLoading ? (
                    <>
                        <LoaderIcon className="w-10 h-10 text-slate-400 animate-spin" />
                        <p className="text-slate-600 font-medium">Đang xử lý...</p>
                        <p className="text-xs text-slate-500">Vui lòng đợi.</p>
                    </>
                ) : (
                    <>
                        <UploadCloudIcon className="w-10 h-10 text-slate-400" />
                        <p className="text-slate-600 font-medium">
                            <span className="text-blue-600">Nhấn để tải lên</span> hoặc kéo và thả
                        </p>
                        <p className="text-xs text-slate-500">Hỗ trợ PNG, JPG, WEBP</p>
                    </>
                )}
            </label>
        </div>
    );
};
