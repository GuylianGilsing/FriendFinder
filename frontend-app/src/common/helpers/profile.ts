export function calculateAge(dateOfBirth: Date): number {
    const currentDate = new Date(Date.now());
    const userBornYear = currentDate.getUTCFullYear() - dateOfBirth.getUTCFullYear();

    const userHadBirthday = currentDate.getUTCDate() >= dateOfBirth.getUTCDate() &&
                            currentDate.getUTCMonth() >= dateOfBirth.getUTCMonth();

    return userHadBirthday ? userBornYear + 1 : userBornYear;
}
