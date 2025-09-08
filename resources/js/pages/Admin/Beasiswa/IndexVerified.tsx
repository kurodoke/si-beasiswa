import AppLayout from '@/layouts/app-layout';
import { Head, useForm } from '@inertiajs/react';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import beasiswa from '@/routes/admin/beasiswa';

export default function Index({ beasiswas }) {
    const { data, setData, post, processing, errors, reset } = useForm({
        nama_beasiswa: '',
        penyelenggara: '',
    });

    function submit(e) {
        e.preventDefault();
        post(beasiswa.store.url(), {
            onSuccess: () => reset(),
        });
    }
    return (
        <AppLayout>
            <Head title='Manajemen Beasiswa' />
            <div className='space-y-6'>
                <Card>
                    <CardHeader>
                        <CardTitle>Tambah Beasiswa Baru</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <form onSubmit={submit} className='flex items-end gap-4'>
                            <div className='flex-1'>
                                <Label>Nama Beasiswa</Label>
                                <Input value={data.nama_beasiswa} onChange={(e) => setData('nama_beasiswa', e.target.value)} />
                                {errors.nama_beasiswa && <p className='text-red-500 text-xs mt-1'>{errors.nama_beasiswa}</p>}
                            </div>
                            <div className='flex-1'>
                                <Label>Penyelenggara</Label>
                                <Input value={data.penyelenggara} onChange={(e) => setData('penyelenggara', e.target.value)} />
                                 {errors.penyelenggara && <p className='text-red-500 text-xs mt-1'>{errors.penyelenggara}</p>}
                            </div>
                            <Button type='submit' disabled={processing}>Simpan</Button>
                        </form>
                    </CardContent>
                </Card>

                <Card>
                     <CardHeader>
                        <CardTitle>Daftar Beasiswa</CardTitle>
                    </CardHeader>
                    <CardContent>
                         <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>Nama Beasiswa</TableHead>
                                    <TableHead>Penyelenggara</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                {beasiswas.map(item => (
                                    <TableRow key={item.id}>
                                        <TableCell>{item.nama_beasiswa}</TableCell>
                                        <TableCell>{item.penyelenggara}</TableCell>
                                    </TableRow>
                                ))}
                            </TableBody>
                        </Table>
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}