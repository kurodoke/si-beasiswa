import AuthenticatedSessionController from '@/actions/App/Http/Controllers/Auth/AuthenticatedSessionController';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthLayout from '@/layouts/auth-layout';
import { home } from '@/routes';
import { Form, Head, Link } from '@inertiajs/react';
import { ArrowLeftIcon, Eye, EyeOff, LoaderCircle } from 'lucide-react';
import { useState } from 'react';

interface LoginProps {
    status?: string;
    canResetPassword: boolean;
}

export default function Login({ status, canResetPassword }: LoginProps) {
    const [showPassword, setShowPassword] = useState(false);

    return (
        <AuthLayout
            title="Masuk ke Sistem"
            description="Gunakan email dan password yang diberikan oleh admin untuk masuk"
        >
            <Head title="Log in" />

            <Form
                {...AuthenticatedSessionController.store.form()}
                resetOnSuccess={['password']}
                className="flex flex-col gap-6"
            >
                {({ processing, errors }) => (
                    <>
                        <div className="grid gap-6">
                            {/* Email */}
                            <div className="grid gap-2">
                                <Label htmlFor="email">Email</Label>
                                <Input
                                    id="email"
                                    type="email"
                                    name="email"
                                    required
                                    autoFocus
                                    tabIndex={1}
                                    autoComplete="email"
                                    placeholder="email@email.com"
                                />
                                <InputError message={errors.email} />
                            </div>

                            {/* Password with Eye Toggle */}
                            <div className="grid gap-2">
                                <Label htmlFor="password">Password</Label>
                                <div className="relative">
                                    <Input
                                        id="password"
                                        type={showPassword ? 'text' : 'password'}
                                        name="password"
                                        required
                                        tabIndex={2}
                                        autoComplete="current-password"
                                        placeholder="Password"
                                        className="pr-10"
                                    />
                                    <button
                                        type="button"
                                        onClick={() => setShowPassword(!showPassword)}
                                        className="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500"
                                        tabIndex={-1}
                                    >
                                        {showPassword ? <EyeOff size={18} /> : <Eye size={18} />}
                                    </button>
                                </div>
                                <InputError message={errors.password} />
                            </div>

                            {/* Submit & Back Buttons */}
                            <div className="mt-4 flex flex-col gap-2">
                                <Button type="submit" className="w-full" tabIndex={4} disabled={processing}>
                                    {processing && <LoaderCircle className="h-4 w-4 animate-spin" />}
                                    Log in
                                </Button>
                                <Button
                                    variant={'ghost'}
                                    type="button"
                                    className="w-full"
                                    tabIndex={4}
                                    disabled={processing}
                                    asChild
                                >
                                    <Link href={home().url}>
                                        <ArrowLeftIcon />
                                        Kembali
                                    </Link>
                                </Button>
                            </div>
                        </div>
                    </>
                )}
            </Form>

            {/* Status Message */}
            {status && (
                <div className="mb-4 text-center text-sm font-medium text-green-600">{status}</div>
            )}
        </AuthLayout>
    );
}
