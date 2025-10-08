
import ExcelJS, { Borders } from 'exceljs';
import { SummaryData } from '../types';

// Helper to get image data as base64 from a DOM element.
// This avoids fetch errors by using the already-loaded image on the page.
const getImageDataFromDOM = (imageId: string): Promise<string | null> => {
    return new Promise((resolve) => {
        const imgElement = document.getElementById(imageId) as HTMLImageElement;
        if (!imgElement) {
            console.error(`Image element with id #${imageId} not found.`);
            return resolve(null);
        }

        const img = new Image();
        img.crossOrigin = "Anonymous"; // Important for canvas security

        img.onload = () => {
            try {
                const canvas = document.createElement('canvas');
                canvas.width = img.naturalWidth;
                canvas.height = img.naturalHeight;
                const ctx = canvas.getContext('2d');
                if (!ctx) {
                    throw new Error('Could not get canvas context');
                }
                ctx.drawImage(img, 0, 0);
                const base64 = canvas.toDataURL('image/png').split(',')[1];
                resolve(base64);
            } catch (e) {
                console.error("Error converting image to base64 using canvas:", e);
                resolve(null);
            }
        };

        img.onerror = () => {
            console.error(`Failed to load image for canvas from src: ${imgElement.src}`);
            resolve(null);
        };
        
        // Setting the src triggers the load. The browser will use its cache if available.
        img.src = imgElement.src;
    });
};

const base64ToBuffer = (base64: string): ArrayBuffer => {
    const binaryString = window.atob(base64);
    const len = binaryString.length;
    const bytes = new Uint8Array(len);
    for (let i = 0; i < len; i++) {
        bytes[i] = binaryString.charCodeAt(i);
    }
    return bytes.buffer;
};

function calcHoursFromTimeRange(timeRange: string): number {
    const match = timeRange.match(/(\d{1,2}):(\d{2})\s*[–-]\s*(\d{1,2}):(\d{2})/);
    if (!match) return 0;
    const start = parseInt(match[1]) + parseInt(match[2]) / 60;
    const end = parseInt(match[3]) + parseInt(match[4]) / 60;
    return Math.max(0, end - start);
}

function getGroupCount(classInfo: string): number {
    const match = classInfo.match(/(\d+)\s*nhóm/i);
    return match ? parseInt(match[1]) : 1;
}

export const exportSummaryToExcel = async (data: SummaryData) => {
  const wb = new ExcelJS.Workbook();
  // Dùng tháng/năm hiện tại nếu không được cung cấp
  const nowForHeader = new Date();
  const useMonth = data.month || (nowForHeader.getMonth() + 1);
  const useYear = data.year || nowForHeader.getFullYear();
  const monthStr = String(useMonth).padStart(2, '0');
  const sheetName = `ThongKe_T${monthStr}_${useYear}`;
  const ws = wb.addWorksheet(sheetName);

  // 🧱 Thiết lập cấu trúc cột & chiều rộng
  ws.columns = [
    { key: 'A', width: 6 },
    { key: 'B', width: 30 },
    { key: 'C', width: 30 },
    { key: 'D', width: 10 },
    { key: 'E', width: 25 },
  ];

  const borderAll: Partial<Borders> = { top: { style: 'thin' }, left: { style: 'thin' }, bottom: { style: 'thin' }, right: { style: 'thin' } };
  const baseFont = { name: 'Times New Roman' };

  const styles = {
    headerVN: { font: { ...baseFont, size: 14, bold: false }, alignment: { horizontal: 'center', vertical: 'middle', wrapText: true } },
    headerEN: { font: { ...baseFont, size: 19, bold: true }, alignment: { horizontal: 'center', vertical: 'middle', wrapText: true } },
    title: { font: { ...baseFont, size: 13, bold: true }, alignment: { horizontal: 'center', vertical: 'middle' } },
    subtitle: { font: { ...baseFont, size: 11, bold: true }, alignment: { horizontal: 'center', vertical: 'middle' } },
    tableHeader: { font: { ...baseFont, bold: true }, alignment: { horizontal: 'center', vertical: 'middle', wrapText: true }, fill: { type: 'pattern', pattern:'solid', fgColor: { argb: 'C5C5C5' } } },
    default: { font: { ...baseFont, size: 11 }, alignment: { vertical: 'middle', wrapText: true } },
    center: { font: { ...baseFont, size: 11 }, alignment: { horizontal: 'center', vertical: 'middle', wrapText: true } },
    groupRow: { font: { ...baseFont, size: 11, bold: true }, fill: { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFFFFF' } }, alignment: { horizontal: 'left', vertical: 'middle', wrapText: true } },
    signatureBold: { font: { ...baseFont, size: 11, bold: true }, alignment: { horizontal: 'center', vertical: 'middle' } },
    signatureItalic: { font: { ...baseFont, size: 11, italic: true }, alignment: { horizontal: 'center', vertical: 'middle' } },
    note: { font: { ...baseFont, size: 11 }, alignment: { vertical: 'top', wrapText: true } },
  };

  let rowIndex = 1;

  // 🧾 Header thông tin + merge
  const logoBase64 = await getImageDataFromDOM('cusc-logo-img');
  if (logoBase64) {
      const logoBuffer = base64ToBuffer(logoBase64);
      const logoImageId = wb.addImage({ buffer: logoBuffer, extension: 'png' });
      ws.addImage(logoImageId, { tl: { col: 0.5, row: 0.5 }, ext: { width: 80, height: 50 } });
  }

  ws.mergeCells('A1:E1');
  ws.mergeCells('A2:E2');
  ws.getCell('A2').value = 'TRUNG TÂM CÔNG NGHỆ PHẦN MỀM ĐẠI HỌC CẦN THƠ';
  Object.assign(ws.getCell('A2'), styles.headerVN);
  
  ws.mergeCells('A3:E3');
  ws.getCell('A3').value = 'CANTHO UNIVERSITY SOFTWARE CENTER';
  Object.assign(ws.getCell('A3'), styles.headerEN);
  
  ws.mergeCells('A4:E4');
  ws.getCell('A4').value = 'Khu III, Đại học Cần Thơ - 01 Lý Tự Trọng, TP. Cần Thơ  - Tel: 0292.3731072 & Fax: 0292.3731071 - Email: cusc@ctu.edu.vn';
  Object.assign(ws.getCell('A4'), { font: { ...baseFont, size: 10, italic: true, underline: true }, alignment: { horizontal: 'left', vertical: 'middle', wrapText: true } });

  rowIndex = 6;
  const next = () => ws.getRow(rowIndex++);

  // 📝 Tiêu đề bảng
  const titleRow = next();
  titleRow.height = 25;
  ws.mergeCells(titleRow.number, 1, titleRow.number, 5);
  titleRow.getCell(1).value = 'BẢNG THỐNG KÊ CÁC BUỔI CHẤM BÁO CÁO ĐỒ ÁN';
  Object.assign(titleRow.getCell(1), styles.title);

  const subtitleRow = next();
  subtitleRow.height = 20;
  ws.mergeCells(subtitleRow.number, 1, subtitleRow.number, 5);
  subtitleRow.getCell(1).value = `THÁNG ${monthStr}-${useYear}`;
  Object.assign(subtitleRow.getCell(1), styles.subtitle);

  // Không hiển thị phần ghi chú trong file Excel theo yêu cầu

  // 🧾 Table header
  const hdr = next();
  hdr.height = 20;
  const headers = ['STT','HỌ VÀ TÊN','CÔNG VIỆC','SỐ GIỜ','GHI CHÚ'];
  headers.forEach((h, i) => {
    const cell = hdr.getCell(i + 1);
    cell.value = h;
    Object.assign(cell, styles.tableHeader);
    cell.border = borderAll;
  });

  // 📋 Nội dung chính
  let sttCounter = 1;
  data.entries.forEach(entry => {
    // Hàng nhóm 1: "Ngày"
    const gr1 = next();
    gr1.height = 20;
    gr1.getCell(1).value = 'Ngày';
    Object.assign(gr1.getCell(1), { ...styles.default, font: { ...baseFont, size: 11, bold: true }, alignment: { horizontal: 'left', vertical: 'middle' } });
    gr1.getCell(1).border = borderAll;
    ws.mergeCells(gr1.number, 2, gr1.number, 5);
    gr1.getCell(2).value = `${entry.date} (${entry.timeRange})`;
    Object.assign(gr1.getCell(2), styles.groupRow);
    gr1.getCell(2).border = borderAll;

    // Hàng nhóm 2: "Lớp:"
    const gr2 = next();
    gr2.height = 20;
    gr2.getCell(1).value = 'Lớp:';
    Object.assign(gr2.getCell(1), { ...styles.default, font: { ...baseFont, size: 11, bold: true }, alignment: { horizontal: 'left', vertical: 'middle' } });
    gr2.getCell(1).border = borderAll;
    ws.mergeCells(gr2.number, 2, gr2.number, 5);
    gr2.getCell(2).value = `${entry.classInfo}`;
    Object.assign(gr2.getCell(2), styles.groupRow);
    gr2.getCell(2).border = borderAll;

    // Xác định ghi chú gộp cho phiên (ưu tiên Năm 2/1 hoặc Học kỳ 2/1)
    const sessionNote = (() => {
      const normalize = (t: string) => t
        .replace(/HK\s*I\b|Học\s*kỳ\s*I\b/gi, 'Học kỳ 1')
        .replace(/HK\s*II\b|Học\s*kỳ\s*II\b/gi, 'Học kỳ 2');
      const noteText = (entry.instructors.map(i => i.notes || '')).map(normalize).join(' | ');
      if (/Năm\s*2|Học\s*kỳ\s*2/i.test(noteText)) return 'Năm 2';
      if (/Năm\s*1|Học\s*kỳ\s*1/i.test(noteText)) return 'Năm 1';
      return '';
    })();

    // Ghi nhận dòng bắt đầu để merge cột GHI CHÚ (E)
    let mergeStartRow = 0;
    let mergeEndRow = 0;

    entry.instructors.forEach((inst, instIndex) => {
      const r = next();
      r.height = 18;
      if (instIndex === 0) mergeStartRow = r.number;
      // Logic số giờ fallback như cũ
      let hours = inst.hours;
      if ((hours === undefined || hours === null || hours === 0)) {
        if (/hướng\s*dẫn|hương\s*dận/i.test(inst.role)) {
          hours = calcHoursFromTimeRange(entry.timeRange);
        } else if (/phản\s*biện/i.test(inst.role)) {
          hours = calcHoursFromTimeRange(entry.timeRange);
        } else if (inst.isChamDoAn) {
          // Ưu tiên lấy năm học từ entry.semester
          let yearType = 1;
          if ((entry as any).semester) {
            if (/2/.test((entry as any).semester)) yearType = 2;
            if (/1/.test((entry as any).semester)) yearType = 1;
          } else if (/năm\s*2/i.test(inst.notes)) {
            yearType = 2;
          }
          hours = (yearType === 1 ? 1.5 * getGroupCount(entry.classInfo) : 2.0 * getGroupCount(entry.classInfo));
        }
      }
      
      // Không ghi chú từng dòng; sẽ gộp sau
      const noteValue = '';

      const vals = [
        inst.stt ?? (instIndex + 1),
        inst.name,
        inst.role,
        hours,
        noteValue
      ];
      vals.forEach((v, i) => {
        const c = r.getCell(i + 1);
        c.value = v;
        if (i === 3 && typeof v === 'number') {
            c.numFmt = '0.00';
        }
        Object.assign(c, i === 0 || i === 3 ? styles.center : styles.default);
        c.border = borderAll;
      });
      mergeEndRow = r.number;
    });

    // Gộp ô ghi chú cho cả phiên và căn giữa
    if (mergeStartRow && mergeEndRow && mergeEndRow >= mergeStartRow) {
      ws.mergeCells(mergeStartRow, 5, mergeEndRow, 5);
      const mergedCell = ws.getCell(mergeStartRow, 5);
      mergedCell.value = sessionNote;
      Object.assign(mergedCell, styles.center);
      mergedCell.border = borderAll;
    }
  });

  next(); // spacer

  // ✍️ Chữ ký cuối
  const sigDateRow = next();
  sigDateRow.height = 18;
  ws.mergeCells(sigDateRow.number, 2, sigDateRow.number, 3);
  // Tự động hiển thị "Ngày .. tháng .. năm .." theo thời điểm hiện tại nếu chưa có
  const now = new Date();
  const defaultSig = `Ngày ${String(now.getDate()).padStart(2, '0')} tháng ${String(now.getMonth() + 1).padStart(2, '0')} năm ${now.getFullYear()}`;
  const sigText = (data.signatureDate && data.signatureDate.trim().length > 0) ? data.signatureDate : defaultSig;
  sigDateRow.getCell(2).value = sigText;
  Object.assign(sigDateRow.getCell(2), styles.signatureItalic);
  ws.mergeCells(sigDateRow.number, 4, sigDateRow.number, 5);
  sigDateRow.getCell(4).value = sigText;
  Object.assign(sigDateRow.getCell(4), styles.signatureItalic);
  
  const sigTitleRow = next();
  sigTitleRow.height = 18;
  ws.mergeCells(sigTitleRow.number, 2, sigTitleRow.number, 3);
  sigTitleRow.getCell(2).value = 'NGƯỜI LẬP';
  Object.assign(sigTitleRow.getCell(2), styles.signatureBold);
  ws.mergeCells(sigTitleRow.number, 4, sigTitleRow.number, 5);
  sigTitleRow.getCell(4).value = 'P. TRƯỞNG BP ĐÀO TẠO';
  Object.assign(sigTitleRow.getCell(4), styles.signatureBold);
  
  const sigSubTitleRow = next();
  sigSubTitleRow.height = 18;
  ws.mergeCells(sigSubTitleRow.number, 2, sigSubTitleRow.number, 3);
  sigSubTitleRow.getCell(2).value = '(Ký, họ tên)';
  Object.assign(sigSubTitleRow.getCell(2), styles.signatureItalic);
  ws.mergeCells(sigSubTitleRow.number, 4, sigSubTitleRow.number, 5);
  sigSubTitleRow.getCell(4).value = '(Ký, họ tên)';
  Object.assign(sigSubTitleRow.getCell(4), styles.signatureItalic);

  rowIndex += 5; // spacer for signature space
  
  const sigNameRow = ws.getRow(rowIndex);
  sigNameRow.height = 18;
  ws.mergeCells(sigNameRow.number, 2, sigNameRow.number, 3);
  sigNameRow.getCell(2).value = data.preparer;
  Object.assign(sigNameRow.getCell(2), styles.signatureBold);
  ws.mergeCells(sigNameRow.number, 4, sigNameRow.number, 5);
  sigNameRow.getCell(4).value = data.approver;
  Object.assign(sigNameRow.getCell(4), styles.signatureBold);


  // ✅ Ghi file to buffer and trigger download in the browser
  const buffer = await wb.xlsx.writeBuffer();
  const blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
  const link = document.createElement('a');
  link.href = URL.createObjectURL(blob);
  link.download = `BangThongKe_T${monthStr}_${useYear}.xlsx`;
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
  URL.revokeObjectURL(link.href);
};
