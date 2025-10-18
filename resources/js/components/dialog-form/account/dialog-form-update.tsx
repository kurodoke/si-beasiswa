import UserManagementController from '@/actions/App/Http/Controllers/Admin/UserManagementController';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Form } from '@inertiajs/react';
import { Eye, EyeOff, LoaderCircle } from 'lucide-react';
import React from 'react';
import InputError from '../../input-error';
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '../../ui/select';

export function DialogEdit({
    user,
    open,
    setOpen,
    loggedInUser,
}: {
    user: any;
    open: boolean;
    setOpen: (open: boolean) => void;
    loggedInUser: any;
}) {
    const isEditingSelf = loggedInUser?.id === user.id;

    const [password, setPassword] = React.useState('');
    const [showPassword, setShowPassword] = React.useState(false);
    const [showPasswordConfirmation, setShowPasswordConfirmation] = React.useState(false);

    return (
        <Dialog open={open} onOpenChange={setOpen}>
            <DialogContent className="md:max-h-[500px] md:max-w-[700px] lg:max-w-[800px]">
                <Form
                    {...UserManagementController.update.form(user.id)}
                    className="flex flex-col gap-6"
                    resetOnSuccess={['password', 'password_confirmation']}
                    disableWhileProcessing
                >
                    {({ processing, errors }) => (
                        <>
                            <DialogHeader>
                                <DialogTitle>Edit Akun</DialogTitle>
                                <DialogDescription>Perbarui informasi akun di bawah.</DialogDescription>
                            </DialogHeader>
                            <div className="grid grid-cols-4 gap-4 md:grid-cols-12">
                                {/* Nama */}
                                <div className="col-span-4 grid gap-1 md:col-span-6">
                                    <Label htmlFor="name">Nama</Label>
                                    <Input id="name" name="name" defaultValue={user.name} required />
                                    <InputError message={errors.name} className="mt-2" />
                                </div>

                                {/* Email */}
                                <div className="col-span-4 grid gap-1 md:col-span-6">
                                    <Label htmlFor="email">Email</Label>
                                    <Input id="email" type="email" name="email" defaultValue={user.email} required />
                                    <InputError message={errors.email} />
                                </div>

                                {/* Role */}
                                <div className="col-span-4 grid gap-1 md:col-span-12">
                                    <Label htmlFor="role">Role</Label>
                                    <Select name="role" defaultValue={user.role} disabled={isEditingSelf}>
                                        <SelectTrigger className="w-full">
                                            <SelectValue placeholder="Pilih Role..." />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectGroup>
                                                <SelectItem value="admin">Admin</SelectItem>
                                                <SelectItem value="validator">Validator</SelectItem>
                                            </SelectGroup>
                                        </SelectContent>
                                    </Select>
                                    <InputError message={errors.role} />
                                    {isEditingSelf && (
                                        <p className="mt-1 text-sm text-muted-foreground">
                                            Kamu tidak bisa mengubah role akunmu sendiri.
                                        </p>
                                    )}
                                </div>

                                {/* Password */}
                                <div className="col-span-2 grid gap-1 md:col-span-6 relative">
                                    <Label htmlFor="password">Password</Label>
                                    <Input
                                        id="password"
                                        type={showPassword ? 'text' : 'password'}
                                        name="password"
                                        value={password}
                                        onChange={(e) => setPassword(e.target.value)}
                                    />
                                    <button
                                        type="button"
                                        className="absolute right-3 top-7 text-gray-500"
                                        onClick={() => setShowPassword(!showPassword)}
                                        tabIndex={-1}
                                    >
                                        {showPassword ? <EyeOff size={18} /> : <Eye size={18} />}
                                    </button>
                                    <InputError message={errors.password} />
                                </div>

                                {/* Konfirmasi Password */}
                                <div className="col-span-2 grid gap-1 md:col-span-6 relative">
                                    <Label htmlFor="password_confirmation">Konfirmasi Password</Label>
                                    <Input
                                        id="password_confirmation"
                                        type={showPasswordConfirmation ? 'text' : 'password'}
                                        name="password_confirmation"
                                        disabled={!password}
                                    />
                                    <button
                                        type="button"
                                        className="absolute right-3 top-7 text-gray-500"
                                        onClick={() =>
                                            setShowPasswordConfirmation(!showPasswordConfirmation)
                                        }
                                        tabIndex={-1}
                                    >
                                        {showPasswordConfirmation ? <EyeOff size={18} /> : <Eye size={18} />}
                                    </button>
                                    <InputError message={errors.password_confirmation} />
                                </div>
                            </div>

                            <DialogFooter>
                                <DialogClose asChild>
                                    <Button variant="outline">Batal</Button>
                                </DialogClose>
                                <Button type="submit" disabled={processing}>
                                    {processing && <LoaderCircle className="h-4 w-4 animate-spin mr-2" />}
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
