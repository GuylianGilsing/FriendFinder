export default async function Request(promise: Promise<any>)
{
    try {
        const response = await promise;
        return [response, null];
    } catch(error) {
        return [null, error];
    }
}
