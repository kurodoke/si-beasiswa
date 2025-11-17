import AppLayout from '@/layouts/public-app-layout';
import { Auth, Berita, LaporanBeasiswa, Periode } from '@/types';
import { Head } from '@inertiajs/react';

import { Blog } from '@/components/blog/blog';
import { DataTable } from '@/components/data-table/public/data-table';
import { Hero } from '@/components/hero/public/hero';
import { Card, CardContent, CardHeader } from '@/components/ui/card';
import { ChartContainer, ChartTooltip, ChartTooltipContent } from '@/components/ui/chart';
import { Separator } from '@/components/ui/separator';
import React from 'react';
import { Bar, BarChart, CartesianGrid, Cell, Pie, PieChart, XAxis, Legend } from 'recharts';

interface LaporanPerPeriode {
    id: number;
    periode: string;
    bulan_mulai: number;
    tahun_mulai: number;
    bulan_selesai: number;
    tahun_selesai: number;
    jumlah_laporan: number;
}

interface DisplayTotalLaporanPerPeriode {
    periode: string;
    jumlah_laporan: number;
}

interface DisplayPersentaseLaporanPeBeasiswa {
    id: number;
    nama_beasiswa: string;
    jenis_beasiswa: string;
    jumlah_laporan: number;
}

export default function Dashboard({
    auth,
    laporan,
    berita,
    total_laporan,
    laporan_per_periode,
    periode_aktif,
    laporan_per_beasiswa,
}: {
    auth: Auth;
    laporan: LaporanBeasiswa[];
    berita: Berita[];
    total_laporan: number;
    laporan_per_periode: LaporanPerPeriode[];
    periode_aktif: Periode;
    laporan_per_beasiswa: DisplayPersentaseLaporanPeBeasiswa[];
}) {
    const [laporanPerPeriode, setLaporanPerPeriode] = React.useState<DisplayTotalLaporanPerPeriode[]>([]);
    const [laporanPerBeasiswa, setLaporanPerBeasiswa] = React.useState<{ name: string; value: number }[]>([]);

    // Warna orange (pie chart)
    const pieColors = [
        '#FFA726', // oranye
        '#66BB6A', // hijau
        '#42A5F5', // biru
        '#AB47BC', // ungu
        '#26C6DA', // cyan
        '#FF7043', // merah-oranye
        '#D4E157', // lime
        '#FFCA28', // kuning
        '#8D6E63', // coklat
        '#EC407A', // pink
    ];

    // Warna biru (bar chart)
    const barColors = ['#42A5F5', '#1E88E5', '#1976D2', '#1565C0', '#0D47A1', '#64B5F6', '#2196F3', '#1E88E5', '#0288D1', '#03A9F4'];

    React.useEffect(() => {
        if (laporan_per_periode.length !== 0) {
            const sorted = laporan_per_periode.sort((a, b) => {
                if (a.tahun_mulai !== b.tahun_mulai) {
                    return a.tahun_mulai - b.tahun_mulai;
                }
                const order = { Ganjil: 1, Genap: 2 };
                return order[a.periode] - order[b.periode];
            });

            const groupedByYear = Object.values(
                sorted.reduce((acc, item) => {
                    const year = item.tahun_mulai;

                    if (!acc[year]) {
                        acc[year] = { tahun: year };
                    }

                    acc[year][item.periode] = item.jumlah_laporan;

                    return acc;
                }, {}),
            );

            setLaporanPerPeriode(groupedByYear);
        }
    }, [laporan_per_periode]);

    React.useEffect(() => {
        if (laporan_per_beasiswa.length !== 0) {
            const data = laporan_per_beasiswa.map((item) => ({
                name: item.nama_beasiswa,
                value: item.jumlah_laporan,
            }));
            setLaporanPerBeasiswa(data);
        }
    }, [laporan_per_beasiswa]);

    return (
        <>
            <Head title="Dashboard" />
            <AppLayout>
                {/* Hero */}
                <div className="mb-8">
                    <Hero periode={periode_aktif} />
                </div>

                {/* Chart */}
                <div className="mb-8 grid grid-cols-4 gap-4 space-y-4 sm:grid-cols-12">
                    <Card className="@container/card col-span-12 shadow-xs">
                        <CardHeader className="text-center">
                            <h2 className="w-full text-3xl font-semibold text-pretty md:text-4xl lg:text-5xl">Beasiswa Terdaftar</h2>
                            <p className="mb-2 text-muted-foreground md:text-base lg:text-lg">Total Laporan terdaftar per-periode</p>
                        </CardHeader>

                        <CardContent className="grid grid-cols-4 sm:grid-cols-12">
                            {/* Pie Chart */}
                            {/* Pie Chart */}
                            <div className="col-span-4 sm:col-span-5">
                                <ChartContainer
                                    config={{}}
                                    className="mx-auto aspect-square max-h-[300px] w-full pb-0 [&_.recharts-pie-label-text]:fill-foreground"
                                >
                                    <PieChart>
                                        <Legend />
                                        <ChartTooltip
                                            content={<ChartTooltipContent />}
                                            formatter={(value, name) => [`${value} laporan `, <span className="font-bold">{name}</span>]}
                                        />

                                        <Pie
                                            data={laporanPerBeasiswa}
                                            dataKey="value"
                                            nameKey="name"
                                            labelLine={false}
                                            label={false} // matikan label dalam pie
                                            paddingAngle={2} // jarak antar segment (border effect)
                                            outerRadius={100} // atur ukuran pie
                                            innerRadius={40} // buat donut, opsional
                                        >
                                            {laporanPerBeasiswa.map((entry, index) => (
                                                <Cell key={`cell-pie-${index}`} fill={pieColors[index % pieColors.length]} />
                                            ))}
                                        </Pie>
                                    </PieChart>
                                </ChartContainer>
                            </div>

                            {/* Separator */}
                            <div className="col-span-4 flex justify-center py-2 sm:col-span-2 sm:py-0">
                                <Separator orientation={'vertical'} className="hidden border-1 sm:block" />
                                <Separator orientation={'horizontal'} className="block border-1 sm:hidden" />
                            </div>

                            {/* Bar Chart */}
                            <div className="col-span-4 sm:col-span-5">
                                <ChartContainer
                                    config={{}}
                                    className="mx-auto aspect-square max-h-[300px] w-full pb-0 [&_.recharts-pie-label-text]:fill-foreground"
                                >
                                    <BarChart data={laporanPerPeriode}>
                                        <CartesianGrid vertical={false} />
                                        <XAxis dataKey="tahun" axisLine={false} tickLine={false} tickMargin={10} />

                                        <ChartTooltip cursor={false} content={<ChartTooltipContent />} />

                                        {/* ==== Bar untuk Ganjil ==== */}
                                        <Bar dataKey="Ganjil" radius={[8, 8, 0, 0]}>
                                            {laporanPerPeriode.map((_, index) => (
                                                <Cell key={`ganjil-${index}`} fill={barColors[(index * 2) % barColors.length]} />
                                            ))}
                                        </Bar>

                                        {/* ==== Bar untuk Genap ==== */}
                                        <Bar dataKey="Genap" radius={[8, 8, 0, 0]}>
                                            {laporanPerPeriode.map((_, index) => (
                                                <Cell key={`genap-${index}`} fill={barColors[(index * 2 + 1) % barColors.length]} />
                                            ))}
                                        </Bar>
                                    </BarChart>
                                </ChartContainer>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                {/* Berita */}
                <div className="mb-8">
                    <Blog data={berita} />
                </div>

                {/* Beasiswa */}
                <div className="mb-8 grid grid-cols-4 gap-4 space-y-4 sm:grid-cols-12">
                    <Card className="@container/card col-span-12 shadow-xs">
                        <CardHeader className="text-center">
                            <h2 className="w-full text-3xl font-semibold text-pretty md:text-4xl lg:text-5xl">Terdaftar Terbaru</h2>
                            <p className="mb-2 text-muted-foreground md:text-base lg:text-lg">
                                Cuplikan informasi beasiswa dari mahasiswa yang baru diverifikasi
                            </p>
                        </CardHeader>

                        <CardContent className="grid grid-cols-4 sm:grid-cols-12">
                            <div className="col-span-4 sm:col-span-12">
                                <DataTable data={laporan} />
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </AppLayout>
        </>
    );
}
