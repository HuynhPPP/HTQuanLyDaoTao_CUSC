
import { SummaryData } from './types.ts';

const DB_NAME = 'CuscReportDB';
const DB_VERSION = 1;
const FILE_STORE_NAME = 'uploadedFiles';
const REPORT_STORE_NAME = 'savedReports';

let db: IDBDatabase;

export const initDB = (): Promise<boolean> => {
  return new Promise((resolve, reject) => {
    if (db) {
      return resolve(true);
    }

    const request = indexedDB.open(DB_NAME, DB_VERSION);

    request.onerror = () => {
      console.error('Database error:', request.error);
      reject(false);
    };

    request.onsuccess = () => {
      db = request.result;
      resolve(true);
    };

    request.onupgradeneeded = () => {
      const dbInstance = request.result;
      if (!dbInstance.objectStoreNames.contains(FILE_STORE_NAME)) {
        dbInstance.createObjectStore(FILE_STORE_NAME, { keyPath: 'name' });
      }
      if (!dbInstance.objectStoreNames.contains(REPORT_STORE_NAME)) {
        dbInstance.createObjectStore(REPORT_STORE_NAME, { keyPath: 'id' });
      }
    };
  });
};

const performDbRequest = <T>(request: IDBRequest): Promise<T> => {
    return new Promise((resolve, reject) => {
        request.onsuccess = () => resolve(request.result as T);
        request.onerror = () => reject(request.error);
    });
}

// --- File Operations ---

export const saveFile = (file: File): Promise<void> => {
    const transaction = db.transaction([FILE_STORE_NAME], 'readwrite');
    const store = transaction.objectStore(FILE_STORE_NAME);
    return performDbRequest<void>(store.put(file));
};

export const getAllFiles = (): Promise<File[]> => {
    const transaction = db.transaction([FILE_STORE_NAME], 'readonly');
    const store = transaction.objectStore(FILE_STORE_NAME);
    return performDbRequest<File[]>(store.getAll());
};

export const deleteFile = (fileName: string): Promise<void> => {
    const transaction = db.transaction([FILE_STORE_NAME], 'readwrite');
    const store = transaction.objectStore(FILE_STORE_NAME);
    return performDbRequest<void>(store.delete(fileName));
};

export const clearAllFiles = (): Promise<void> => {
    const transaction = db.transaction([FILE_STORE_NAME], 'readwrite');
    const store = transaction.objectStore(FILE_STORE_NAME);
    return performDbRequest<void>(store.clear());
};


// --- Report Operations ---

export const saveReport = (report: SummaryData): Promise<void> => {
    const transaction = db.transaction([REPORT_STORE_NAME], 'readwrite');
    const store = transaction.objectStore(REPORT_STORE_NAME);
    return performDbRequest<void>(store.put(report));
};

export const getAllReports = (): Promise<SummaryData[]> => {
    const transaction = db.transaction([REPORT_STORE_NAME], 'readonly');
    const store = transaction.objectStore(REPORT_STORE_NAME);
    return performDbRequest<SummaryData[]>(store.getAll());
};

export const deleteReport = (reportId: string): Promise<void> => {
    const transaction = db.transaction([REPORT_STORE_NAME], 'readwrite');
    const store = transaction.objectStore(REPORT_STORE_NAME);
    return performDbRequest<void>(store.delete(reportId));
};

export const clearAllReports = (): Promise<void> => {
    const transaction = db.transaction([REPORT_STORE_NAME], 'readwrite');
    const store = transaction.objectStore(REPORT_STORE_NAME);
    return performDbRequest<void>(store.clear());
};
