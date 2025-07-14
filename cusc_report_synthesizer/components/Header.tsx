
import React from 'react';

export const Header: React.FC = () => {
    return (
        <header className="bg-white shadow-sm no-print">
            <div className="container mx-auto px-4 sm:px-6 lg:px-8">
                <div className="flex items-center justify-between h-20">
                    <div className="flex items-center gap-3">
                         <img src="/Images/banner_cusc.png" alt="CUSC Logo" className="h-14" id="cusc-logo-img"/>
                        <div>
                           <h1 className="text-lg font-bold text-slate-800">Hệ thống tổng hợp bảng thống kê báo cáo đồ án - CUSC</h1>
                           <p className="text-sm text-slate-500">Công cụ thông minh để tổng hợp lịch báo cáo đồ án</p>
                        </div>
                    </div>
                </div>
            </div>
        </header>
    );
};