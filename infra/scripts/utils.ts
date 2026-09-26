// Test data only: Math.random() is not a cryptographic source.
export function between(min: number, max: number): number {
    // Integer in [min, max): the floor keeps amounts packable as `nat`
    // in the merkle leaf, see docs/README.md.
    return Math.floor(Math.random() * (max - min) + min);
}
