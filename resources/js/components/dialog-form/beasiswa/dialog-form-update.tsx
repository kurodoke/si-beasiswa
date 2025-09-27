import BeasiswaController from '@/actions/App/Http/Controllers/Admin/BeasiswaController';
import { Button } from '@/components/ui/button';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Form } from '@inertiajs/react';
import { LoaderCircle } from 'lucide-react';
import React from 'react';
import InputError from '../../input-error';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '../../ui/select';

export function DialogEdit({ data, open, setOpen }: { data: any; open: boolean; setOpen: (open: boolean) => void }) {
    const [jenisBeasiswaValue, setJenisBeasiswaValue] = React.useState('');

    React.useEffect(() => {
        setJenisBeasiswaValue(data.jenis_beasiswa);
    }, [data]);

    return (
        <Dialog open={open} onOpenChange={setOpen}>
            <DialogContent>
                <Form {...BeasiswaController.update.form(data.id)} className="flex flex-col gap-6" disableWhileProcessing>
                    {({ processing, errors }) => (
                        <>
                            <DialogHeader>
                                <DialogTitle>Edit Jenis Beasiswa</DialogTitle>
                                <DialogDescription>Perbarui informasi jenis beasiswa di bawah.</DialogDescription>
                            </DialogHeader>
                            <div className="grid grid-cols-4 gap-4 md:grid-cols-12">
                                <div className="col-span-4 grid gap-1 md:col-span-12">
                                    <Label htmlFor="nama_beasiswa">Beasiswa</Label>
                                    <Input id="nama_beasiswa" name="nama_beasiswa" defaultValue={data.nama_beasiswa} required />
                                    <InputError message={errors.beasiswa} className="mt-2" />
                                </div>
                                <div className="col-span-4 grid gap-1 md:col-span-12">
                                    <Label htmlFor="jenis_beasiswa">Jenis Beasiswa</Label>
                                    <Input id="jenis_beasiswa" type="text" name="jenis_beasiswa" value={jenisBeasiswaValue} required hidden />
                                    <Select
                                        name="jenis_beasiswa"
                                        required
                                        onValueChange={(value) => {
                                            setJenisBeasiswaValue(value);
                                        }}
                                        value={jenisBeasiswaValue}
                                    >
                                        <SelectTrigger className="w-full" id="jenis_beasiswa">
                                            <SelectValue placeholder="Pilih Jenis Beasiswa" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem value="Internasional">Internasional</SelectItem>
                                            <SelectItem value="Nasional">Nasional</SelectItem>
                                            <SelectItem value="Regional">Regional</SelectItem>
                                        </SelectContent>
                                    </Select>
                                    <InputError message={errors.jenis_beasiswa} className="mt-2" />
                                </div>
                            </div>

                            <DialogFooter>
                                <DialogClose asChild>
                                    <Button variant="outline">Batal</Button>
                                </DialogClose>
                                <Button type="submit" disabled={processing}>
                                    {processing && <LoaderCircle className="h-4 w-4 animate-spin" />}
                                    {processing ? 'Menyimpan...' : 'Simpan'}
                                </Button>
                            </DialogFooter>
                        </>
                    )}
                </Form>
            </DialogContent>
        </Dialog>
    );
}
