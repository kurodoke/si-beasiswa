import UserManagementController from '@/actions/App/Http/Controllers/Admin/UserManagementController';
import { Form } from '@inertiajs/react';
import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
} from '../../ui/alert-dialog';
import BeasiswaController from '@/actions/App/Http/Controllers/Admin/BeasiswaController';

export function DialogDelete({
    data,
    open,
    setOpen,
}: {
    data: any;
    open: boolean;
    setOpen: (open: boolean) => void;
}) {
    return (
        <AlertDialog open={open} onOpenChange={setOpen}>
            <AlertDialogContent>
                <AlertDialogHeader>
                    <AlertDialogTitle>Apakah kamu yakin?</AlertDialogTitle>
                    <AlertDialogDescription>
                        Ini akan menghapus jenis beasiswa <span className="font-bold">{data.jenis_beasiswa}</span> beserta data beasiswa berjumlah <span className='font-bold text-lg'>{data.jumlah_beasiswa}</span> secara permanen dan tidak dapat dikembalikan.
                    </AlertDialogDescription>
                </AlertDialogHeader>
                <AlertDialogFooter>
                    <AlertDialogCancel>Tidak</AlertDialogCancel>
                    <Form {...BeasiswaController.destroy.form(data.id)} className='w-full sm:w-auto'>
                        <AlertDialogAction
                            type="submit"
                            className='w-full sm:w-auto'
                        >
                            Ya
                        </AlertDialogAction>
                    </Form>
                </AlertDialogFooter>
            </AlertDialogContent>
        </AlertDialog>
    );
}
