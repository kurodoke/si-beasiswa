import { Head, useForm } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import penerima from '@/routes/admin/penerima';

export default function Create({ beasiswas, programStudis }) {
    const { data, setData, post, processing, errors } = useForm({
        nama_mahasiswa: '',
        npm: '',
        program_studi_id: '',
        beasiswa_id: '',
        tahun_penerimaan: new Date().getFullYear().toString(),
    });

    function submit(e) {
        e.preventDefault();
        post(penerima.store.url());
    }

    return (
        <AppLayout>
            <Head title='Input Data Penerima Beasiswa' />
            <Card>
                <CardHeader>
                    <CardTitle>Input Data Penerima</CardTitle>
                    <CardDescription>
                        Form ini untuk admin menambahkan data mahasiswa penerima beasiswa secara manual.
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <form onSubmit={submit} className='space-y-4 max-w-2xl'>
                        <div>
                            <Label>Nama Mahasiswa</Label>
                            <Input value={data.nama_mahasiswa} onChange={(e) => setData('nama_mahasiswa', e.target.value)} />
                            {errors.nama_mahasiswa && <p className='text-red-500 text-xs mt-1'>{errors.nama_mahasiswa}</p>}
                        </div>
                        <div>
                            <Label>NPM</Label>
                            <Input value={data.npm} onChange={(e) => setData('npm', e.target.value)} />
                            {errors.npm && <p className='text-red-500 text-xs mt-1'>{errors.npm}</p>}
                        </div>
                         <div>
                            <Label>Program Studi</Label>
                            <Select onValueChange={(value) => setData('program_studi_id', value)}>
                                <SelectTrigger><SelectValue placeholder='Pilih prodi...' /></SelectTrigger>
                                <SelectContent>
                                    {programStudis.map((item) => (
                                        <SelectItem key={item.id} value={item.id.toString()}>{item.nama_prodi}</SelectItem>
                                    ))}
                                </SelectContent>
                            </Select>
                            {errors.program_studi_id && <p className='text-red-500 text-xs mt-1'>{errors.program_studi_id}</p>}
                        </div>
                        <div>
                            <Label>Beasiswa yang Diterima</Label>
                            <Select onValueChange={(value) => setData('beasiswa_id', value)}>
                                <SelectTrigger><SelectValue placeholder='Pilih beasiswa...' /></SelectTrigger>
                                <SelectContent>
                                    {beasiswas.map((item) => (
                                        <SelectItem key={item.id} value={item.id.toString()}>{item.nama_beasiswa}</SelectItem>
                                    ))}
                                </SelectContent>
                            </Select>
                            {errors.beasiswa_id && <p className='text-red-500 text-xs mt-1'>{errors.beasiswa_id}</p>}
                        </div>
                        <div>
                            <Label>Tahun Penerimaan</Label>
                            <Input type='number' value={data.tahun_penerimaan} onChange={(e) => setData('tahun_penerimaan', e.target.value)} />
                            {errors.tahun_penerimaan && <p className='text-red-500 text-xs mt-1'>{errors.tahun_penerimaan}</p>}
                        </div>
                        <Button type='submit' disabled={processing}>
                            {processing ? 'Menyimpan...' : 'Simpan Data'}
                        </Button>
                    </form>
                </CardContent>
            </Card>
        </AppLayout>
    );
}