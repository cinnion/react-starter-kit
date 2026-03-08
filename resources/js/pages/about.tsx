import { Head } from '@inertiajs/react';
import SharedLayout from '@/layouts/shared-layout';
import { about } from '@/routes';
import type { BreadcrumbItem, BuildInfo } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'About',
        href: about(),
    },
];


export default function About({ build_info } : { build_info: BuildInfo}) {
    return (
        <SharedLayout breadcrumbs={breadcrumbs}>
            <Head title="About" />

            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                {build_info.Error && (
                    <div className="mx-auto my-4 flex max-w-lg items-center rounded-md bg-orange-400 px-6 py-4">
                        <strong>Error</strong>: {build_info.Error}
                    </div>
                )}
                <div>Build Number: {build_info.BUILD_NUMBER}</div>
                <div>Build ID: {build_info.BUILD_ID}</div>
                <div>Build URL: {build_info.BUILD_URL}</div>
                <div>Git Commit: {build_info.GIT_COMMIT}</div>
                <div>Git Branch: {build_info.GIT_BRANCH}</div>
                <div>Build Date: {build_info.BUILD_DATE}</div>
                <br />
            </div>
        </SharedLayout>
    );
}
