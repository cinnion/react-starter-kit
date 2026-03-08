import { usePage } from '@inertiajs/react';
import React from 'react';
import AppLayout from '@/layouts/app-layout';
import GuestLayout from '@/layouts/guest-layout';
import type { AppLayoutProps } from '@/types';

const SharedLayout = ({ children, breadcrumbs } : AppLayoutProps) => {
    const { auth } = usePage().props;
    const user = auth.user;

    if (user) {
        return <AppLayout breadcrumbs={breadcrumbs}>{children}</AppLayout>;
    } else {
        return <GuestLayout>{children}</GuestLayout>;
    }
}

export default SharedLayout;
