
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
  const monthStr = String(data.month).padStart(2, '0');
  const sheetName = `ThongKe_T${monthStr}_${data.year}`;
  const ws = wb.addWorksheet(sheetName);

  // 🧱 Thiết lập cấu trúc cột & chiều rộng
  ws.columns = [
    { key: 'A', width: 5 },
    { key: 'B', width: 30 },
    { key: 'C', width: 30 },
    { key: 'D', width: 10 },
    { key: 'E', width: 25 },
  ];

  const borderAll: Partial<Borders> = { top: { style: 'thin' }, left: { style: 'thin' }, bottom: { style: 'thin' }, right: { style: 'thin' } };
  const baseFont = { name: 'Times New Roman' };

  const styles = {
    headerTop: { font: { ...baseFont, size: 10, bold: true }, alignment: { horizontal: 'center', vertical: 'middle', wrapText: true } },
    title: { font: { ...baseFont, size: 13, bold: true }, alignment: { horizontal: 'center', vertical: 'middle' } },
    subtitle: { font: { ...baseFont, size: 11, bold: true }, alignment: { horizontal: 'center', vertical: 'middle' } },
    tableHeader: { font: { ...baseFont, bold: true }, alignment: { horizontal: 'center', vertical: 'middle', wrapText: true }, fill: { type: 'pattern', pattern:'solid', fgColor: { argb: 'D9E1F2' } } },
    default: { font: { ...baseFont, size: 11 }, alignment: { vertical: 'middle', wrapText: true } },
    center: { font: { ...baseFont, size: 11 }, alignment: { horizontal: 'center', vertical: 'middle', wrapText: true } },
    groupRow: { font: { ...baseFont, size: 11, bold: true }, fill: { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFF2F2F2' } }, alignment: { horizontal: 'left', vertical: 'middle', wrapText: true } },
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
  Object.assign(ws.getCell('A2'), styles.headerTop);
  
  ws.mergeCells('A3:E3');
  ws.getCell('A3').value = 'CANTHO UNIVERSITY SOFTWARE CENTER';
  Object.assign(ws.getCell('A3'), styles.headerTop);
  
  ws.mergeCells('A4:E4');
  ws.getCell('A4').value = 'Khu III, Đại học Cần Thơ - 01 Lý Tự Trọng, TP. Cần Thơ - Tel: 0292.3731072 & Fax: 0292.3731071';
  Object.assign(ws.getCell('A4'), { ...styles.headerTop, font: {...baseFont, size: 9} });

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
  subtitleRow.getCell(1).value = `THÁNG ${monthStr}-${data.year}`;
  Object.assign(subtitleRow.getCell(1), styles.subtitle);

  next(); // spacer

  // Ghi chú
  const noteRow = next();
  ws.mergeCells(noteRow.number, 1, noteRow.number + 2, 5);
  const noteText = 
    'Ghi chú về cách tính giờ:\n' +
    '• Giờ chấm đồ án (GV Phản biện): 1.5 giờ * số nhóm (Đối với đồ án Năm 1) hoặc 2.0 giờ * số nhóm (Đối với đồ án Năm 2).\n' +
    '• Thời gian buổi báo cáo: Giờ bắt đầu tính theo biên bản, giờ kết thúc được điều chỉnh theo quy chế của trung tâm.';
  noteRow.getCell(1).value = noteText;
  Object.assign(noteRow.getCell(1), styles.note);
  noteRow.getCell(1).border = borderAll;
  rowIndex += 2; // account for merged rows
  
  next(); // spacer

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
    const gr = next();
    gr.height = 18;
    ws.mergeCells(gr.number, 1, gr.number, 5);
    gr.getCell(1).value = `Ngày: ${entry.date} (${entry.timeRange}) | Lớp: ${entry.classInfo}`;
    Object.assign(gr.getCell(1), styles.groupRow);
    gr.getCell(1).border = borderAll;
    gr.getCell(5).border = { right: { style: 'thin' } }; // Right border for merged cell

    // Logic ghi chú - hiển thị notes riêng cho từng dòng
    entry.instructors.forEach((inst, instIndex) => {
      const r = next();
      r.height = 18;
      // Logic số giờ fallback như cũ
      let hours = inst.hours;
      if ((hours === undefined || hours === null || hours === 0)) {
        if (/hướng dẫn/i.test(inst.role)) {
          hours = calcHoursFromTimeRange(entry.timeRange);
        } else if (/phản biện/i.test(inst.role)) {
          const hd = entry.instructors.find(i => /hướng dẫn/i.test(i.role));
          hours = hd ? (hd.hours && hd.hours !== 0 ? hd.hours : calcHoursFromTimeRange(entry.timeRange)) : 0;
        } else if (inst.isChamDoAn) {
          const yearType = /năm\s*2/i.test(inst.notes) ? 2 : 1;
          hours = (yearType === 1 ? 1.5 * getGroupCount(entry.classInfo) : 2.0 * getGroupCount(entry.classInfo));
        }
      }
      
      // Hiển thị notes riêng cho từng dòng
      const noteValue = inst.notes || '';
      
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
    });
  });

  next(); // spacer

  // ✍️ Chữ ký cuối
  const sigDateRow = next();
  sigDateRow.height = 18;
  ws.mergeCells(sigDateRow.number, 2, sigDateRow.number, 3);
  sigDateRow.getCell(2).value = data.signatureDate;
  Object.assign(sigDateRow.getCell(2), styles.signatureItalic);
  ws.mergeCells(sigDateRow.number, 4, sigDateRow.number, 5);
  sigDateRow.getCell(4).value = data.signatureDate;
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
  link.download = `BangThongKe_T${monthStr}_${data.year}.xlsx`;
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
  URL.revokeObjectURL(link.href);
};
