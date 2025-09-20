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
import PeriodeController from '@/actions/App/Http/Controllers/Admin/PeriodeController';

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
                        Ini akan menghapus periode ke-<span className="font-bold">{data.periode}</span> beserta data beasiswa berjumlah <span className='font-bold text-lg'>{data.jumlah_laporan}</span> secara permanen dan tidak dapat dikembalikan.
                    </AlertDialogDescription>
                </AlertDialogHeader>
                <AlertDialogFooter>
                    <AlertDialogCancel>Tidak</AlertDialogCancel>
                    <Form {...PeriodeController.destroy.form(data.id)} className='w-full sm:w-auto'>
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
