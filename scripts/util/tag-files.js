import shell from 'shelljs';
import fs from 'fs';
import path from 'path';

export const getVersionTag = async (fileName = "VERSION") => {
    try {
        const file = path.join(path.dirname("."), fileName);
        const version = await fs.promises.readFile(file, 'utf8')
        return `v${version}`.replace(/(\r\n|\n|\r)/gm, "").trim();
    }
    catch (err) {
        console.log(err)
    }
}

export const createTaggedRelease = (version, notes = "bug fixes") => {
    if (shell.exec(`gh release create ${version} --notes "${notes}"`).code !== 0) {
        shell.echo('Error: gh release failed');
        shell.exit(1);
    }
}

