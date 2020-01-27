import * as assert from 'assert'
import { LineTransform } from './LineTransformation'

export class IgnoreInsecurePasswordWarning extends LineTransform {
  private readonly uneccessaryError =
    '[Warning] Using a password on the command line interface can be insecure.\n'

  transformLine(line: string): void {
    if (!line.endsWith(this.uneccessaryError)) {
      this.push(line)
    }
  }
}
