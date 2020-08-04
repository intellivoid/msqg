clean:
	rm -rf build

build:
	mkdir build
	ppm --compile="src/msqg" --directory="build"

install:
	ppm --no-prompt --install="build/net.intellivoid.msqg.ppm"