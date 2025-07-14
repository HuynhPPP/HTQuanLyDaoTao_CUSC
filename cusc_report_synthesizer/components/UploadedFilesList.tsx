
import React, { useState } from 'react';
import { FileIcon, TrashIcon, SearchIcon, LoaderIcon } from './Icons.tsx';

interface UploadedFilesListProps {
    files: File[];
    selectedFiles: File[];
    onSelectionChange: (file: File, isSelected: boolean) => void;
    onRemoveFile: (fileName: string) => void;
}

export const UploadedFilesList: React.FC<UploadedFilesListProps> = ({ files, selectedFiles, onSelectionChange, onRemoveFile }) => {
    const [searchTerm, setSearchTerm] = useState('');

    const filteredFiles = files.filter(file => 
        file.name.toLowerCase().includes(searchTerm.toLowerCase())
    );
    
    const isFileSelected = (file: File) => {
        return selectedFiles.some(selected => selected.name === file.name);
    }

    const handleSelectAll = (e: React.ChangeEvent<HTMLInputElement>) => {
        if (e.target.checked) {
            filteredFiles.forEach(file => {
                if (!isFileSelected(file)) {
                    onSelectionChange(file, true);
                }
            });
        } else {
             filteredFiles.forEach(file => {
                if (isFileSelected(file)) {
                    onSelectionChange(file, false);
                }
            });
        }
    };
    
    const allFilteredSelected = filteredFiles.length > 0 && filteredFiles.every(isFileSelected);

    if (files.length === 0) {
        return (
             <div className="text-center py-8 text-sm text-slate-500">
                 <p>Tải lên một số tệp để bắt đầu.</p>
            </div>
        )
    }

    return (
        <div>
            <div className="relative mb-2">
                <SearchIcon className="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" />
                <input
                    type="text"
                    placeholder="Tìm kiếm tệp..."
                    value={searchTerm}
                    onChange={(e) => setSearchTerm(e.target.value)}
                    className="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                />
            </div>
            
            <div className="flex items-center justify-between py-2 border-b">
                 <div className="flex items-center">
                    <input
                        type="checkbox"
                        id="select-all"
                        className="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                        checked={allFilteredSelected}
                        onChange={handleSelectAll}
                        disabled={filteredFiles.length === 0}
                    />
                    <label htmlFor="select-all" className="ml-2 text-sm font-medium text-slate-700">Chọn tất cả</label>
                </div>
                <span className="text-sm text-slate-500">{selectedFiles.length} / {files.length} đã chọn</span>
            </div>
            
            <ul className="space-y-1 max-h-60 overflow-y-auto pr-1 mt-2">
                {filteredFiles.length > 0 ? filteredFiles.map((file) => (
                    <li key={file.name} className="flex items-center justify-between bg-slate-50 p-2 rounded-md hover:bg-slate-100 group">
                        <div className="flex items-center overflow-hidden">
                            <input
                                type="checkbox"
                                id={`file-${file.name}`}
                                className="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 flex-shrink-0"
                                checked={isFileSelected(file)}
                                onChange={(e) => onSelectionChange(file, e.target.checked)}
                            />
                            <FileIcon className="w-5 h-5 text-slate-500 mx-2 flex-shrink-0" />
                            <span className="text-sm text-slate-700 truncate" title={file.name}>
                                {file.name}
                            </span>
                        </div>
                        <button 
                            onClick={() => onRemoveFile(file.name)} 
                            className="text-slate-400 hover:text-red-500 opacity-0 group-hover:opacity-100 transition-opacity ml-2 flex-shrink-0"
                            title={`Xóa ${file.name}`}
                        >
                            <TrashIcon className="w-5 h-5"/>
                        </button>
                    </li>
                )) : (
                    <div className="text-center py-8">
                         <p className="text-slate-500">{searchTerm ? 'Không có tệp nào khớp với tìm kiếm của bạn.' : 'Không có tệp nào được tải lên.'}</p>
                    </div>
                )}
            </ul>
        </div>
    );
};
